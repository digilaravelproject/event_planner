<?php

namespace App\Modules\DynamicVendors\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use SimpleXMLElement;
use ZipArchive;

class AttributeSheetService
{
    private const SPREADSHEET_NAMESPACE = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    private const MAX_ROWS = 1000;

    private const MAX_UNCOMPRESSED_BYTES = 10 * 1024 * 1024;

    public function import(UploadedFile $file): array
    {
        $archive = new ZipArchive;
        $path = $file->getRealPath();

        if ($path === false || $archive->open($path) !== true) {
            throw ValidationException::withMessages([
                'attribute_sheet' => 'The attribute sheet must be a valid Excel (.xlsx) file.',
            ]);
        }

        try {
            $this->guardArchiveSize($archive);
            $sharedStrings = $this->sharedStrings($archive);
            $worksheet = $this->firstWorksheet($archive);
        } finally {
            $archive->close();
        }

        $rows = $this->rows($worksheet, $sharedStrings);
        if ($rows === []) {
            throw ValidationException::withMessages([
                'attribute_sheet' => 'The attribute sheet does not contain any rows.',
            ]);
        }

        $header = array_shift($rows);
        if ($this->normalizeHeader($header['A'] ?? null) !== 'attribute name'
            || $this->normalizeHeader($header['B'] ?? null) !== 'attribute value') {
            throw ValidationException::withMessages([
                'attribute_sheet' => 'The first row must contain “Attribute Name” and “Attribute Value”.',
            ]);
        }

        $attributes = [];
        foreach ($rows as $row) {
            $label = trim((string) ($row['A'] ?? ''));
            $value = $row['B'] ?? null;

            if ($label === '' && ($value === null || $value === '')) {
                continue;
            }
            if ($label === '') {
                throw ValidationException::withMessages([
                    'attribute_sheet' => 'Every populated row must have an Attribute Name.',
                ]);
            }
            if (mb_strlen($label) > 255) {
                throw ValidationException::withMessages([
                    'attribute_sheet' => "The attribute name “{$label}” is longer than 255 characters.",
                ]);
            }

            $attributes[] = [
                'label' => $label,
                'value' => $this->stringValue($value),
                'type' => $this->inferType($value),
            ];

            if (count($attributes) > self::MAX_ROWS) {
                throw ValidationException::withMessages([
                    'attribute_sheet' => 'The attribute sheet may contain at most '.self::MAX_ROWS.' attributes.',
                ]);
            }
        }

        if ($attributes === []) {
            throw ValidationException::withMessages([
                'attribute_sheet' => 'Add at least one attribute below the header row.',
            ]);
        }

        return $attributes;
    }

    private function guardArchiveSize(ZipArchive $archive): void
    {
        $total = 0;
        for ($index = 0; $index < $archive->numFiles; $index++) {
            $total += (int) ($archive->statIndex($index)['size'] ?? 0);
            if ($total > self::MAX_UNCOMPRESSED_BYTES) {
                throw ValidationException::withMessages([
                    'attribute_sheet' => 'The uncompressed attribute sheet is too large.',
                ]);
            }
        }
    }

    private function sharedStrings(ZipArchive $archive): array
    {
        $xml = $archive->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }

        $document = $this->xml($xml);
        $strings = [];
        $document->registerXPathNamespace('x', self::SPREADSHEET_NAMESPACE);
        foreach ($document->xpath('//x:si') ?: [] as $item) {
            $strings[] = $this->text($item);
        }

        return $strings;
    }

    private function firstWorksheet(ZipArchive $archive): SimpleXMLElement
    {
        $worksheetPath = null;
        for ($index = 0; $index < $archive->numFiles; $index++) {
            $name = (string) $archive->getNameIndex($index);
            if (preg_match('#^xl/worksheets/sheet\d+\.xml$#', $name) === 1) {
                $worksheetPath = $name;
                break;
            }
        }

        $xml = $worksheetPath === null ? false : $archive->getFromName($worksheetPath);
        if ($xml === false) {
            throw ValidationException::withMessages([
                'attribute_sheet' => 'The Excel file does not contain a readable worksheet.',
            ]);
        }

        return $this->xml($xml);
    }

    private function rows(SimpleXMLElement $worksheet, array $sharedStrings): array
    {
        $worksheet->registerXPathNamespace('x', self::SPREADSHEET_NAMESPACE);
        $rows = [];

        foreach ($worksheet->xpath('//x:sheetData/x:row') ?: [] as $row) {
            $row->registerXPathNamespace('x', self::SPREADSHEET_NAMESPACE);
            $values = [];
            foreach ($row->xpath('./x:c') ?: [] as $cell) {
                $reference = (string) $cell['r'];
                if (preg_match('/^([A-Z]+)/i', $reference, $matches) !== 1) {
                    continue;
                }

                $column = strtoupper($matches[1]);
                if (! in_array($column, ['A', 'B'], true)) {
                    continue;
                }

                $values[$column] = $this->cellValue($cell, $sharedStrings);
            }
            $rows[] = $values;
        }

        return $rows;
    }

    private function cellValue(SimpleXMLElement $cell, array $sharedStrings): mixed
    {
        $cell->registerXPathNamespace('x', self::SPREADSHEET_NAMESPACE);
        $type = (string) $cell['t'];
        if ($type === 'inlineStr') {
            $inlineString = $cell->xpath('./x:is')[0] ?? null;

            return $inlineString instanceof SimpleXMLElement ? $this->text($inlineString) : '';
        }

        $raw = (string) ($cell->xpath('./x:v')[0] ?? '');
        if ($type === 's') {
            return $sharedStrings[(int) $raw] ?? '';
        }
        if ($type === 'b') {
            return $raw === '1';
        }
        if (in_array($type, ['str', 'e'], true)) {
            return $raw;
        }
        if ($raw === '') {
            return '';
        }

        $number = (float) $raw;

        return floor($number) === $number ? (int) $number : $number;
    }

    private function text(SimpleXMLElement $element): string
    {
        $element->registerXPathNamespace('x', self::SPREADSHEET_NAMESPACE);
        $value = '';
        foreach ($element->xpath('.//x:t') ?: [] as $text) {
            $value .= (string) $text;
        }

        return $value;
    }

    private function xml(string $xml): SimpleXMLElement
    {
        $previous = libxml_use_internal_errors(true);
        try {
            $document = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOCDATA);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        if ($document === false) {
            throw ValidationException::withMessages([
                'attribute_sheet' => 'The Excel file contains invalid worksheet data.',
            ]);
        }

        return $document;
    }

    private function normalizeHeader(mixed $value): string
    {
        return trim((string) preg_replace('/[\s_-]+/', ' ', mb_strtolower((string) $value)));
    }

    private function stringValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return $value === null ? '' : (string) $value;
    }

    private function inferType(mixed $value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value) || is_float($value)) {
            return 'number';
        }

        return 'text';
    }
}
