<?php

namespace App\Services;

use Illuminate\Support\Collection;
use ZipArchive;

class AdminUserExportService
{
    public function rows(Collection $users): array
    {
        return $users->map(fn ($user): array => [
            $user->id,
            $user->name,
            $user->email,
            $user->mobile_number ?: 'N/A',
            $user->subscription?->name ?: 'Free Tier / None',
            $user->subscription_ends_at?->format('d M Y H:i') ?: 'N/A',
            $user->status ? 'Active' : 'Inactive',
            (int) ($user->plans_count ?? 0),
            (float) ($user->plans_total ?? 0),
            $user->created_at?->format('d M Y H:i') ?: '',
        ])->all();
    }

    public function headers(): array
    {
        return ['ID', 'Name', 'Email', 'Mobile', 'Subscription', 'Subscription ends', 'Status', 'Plans', 'Plan value (INR)', 'Registered at'];
    }

    public function pdf(Collection $users): string
    {
        $rows = $this->rows($users);
        $pages = array_chunk($rows, 20) ?: [[]];
        $streams = [];
        $columns = [
            ['label' => 'ID', 'index' => 0, 'width' => 28],
            ['label' => 'NAME', 'index' => 1, 'width' => 90],
            ['label' => 'EMAIL', 'index' => 2, 'width' => 130],
            ['label' => 'MOBILE', 'index' => 3, 'width' => 75],
            ['label' => 'SUBSCRIPTION', 'index' => 4, 'width' => 105],
            ['label' => 'SUB ENDS', 'index' => 5, 'width' => 85],
            ['label' => 'STATUS', 'index' => 6, 'width' => 50],
            ['label' => 'PLANS', 'index' => 7, 'width' => 40],
            ['label' => 'PLAN VALUE', 'index' => 8, 'width' => 75, 'format' => 'money'],
            ['label' => 'REGISTERED', 'index' => 9, 'width' => 90],
        ];
        $tableX = 22;
        $tableWidth = array_sum(array_column($columns, 'width'));

        foreach ($pages as $pageIndex => $pageRows) {
            $stream = "0.98 0.98 0.99 rg 0 0 842 595 re f\n";
            $stream .= "0.12 0.18 0.35 rg 0 535 842 60 re f\n";
            $stream .= $this->text(32, 563, 18, 'F2', '1 1 1', 'USER MANAGEMENT EXPORT');
            $stream .= $this->text(32, 544, 8, 'F1', '0.85 0.89 1', 'Generated '.now()->format('d M Y H:i').' | '.count($rows).' users');
            $stream .= "0.23 0.31 0.64 rg {$tableX} 497 {$tableWidth} 27 re f\n";
            $x = $tableX;
            foreach ($columns as $column) {
                $stream .= $this->cellText($x, 506, $column['width'], 5.7, 'F2', '1 1 1', $column['label']);
                $x += $column['width'];
            }
            $y = 475;
            foreach ($pageRows as $index => $row) {
                if ($index % 2 === 0) {
                    $stream .= "0.95 0.96 0.99 rg {$tableX} ".($y - 7)." {$tableWidth} 22 re f\n";
                }
                $x = $tableX;
                foreach ($columns as $column) {
                    $value = $row[$column['index']];
                    if (($column['format'] ?? null) === 'money') {
                        $value = 'Rs. '.number_format((float) $value, 0);
                    }
                    $stream .= $this->cellText($x, $y, $column['width'], 5.7, 'F1', '0.12 0.16 0.24', (string) $value);
                    $x += $column['width'];
                }
                $stream .= "0.86 0.88 0.92 RG 0.35 w {$tableX} ".($y - 8).' m '.($tableX + $tableWidth).' '.($y - 8)." l S\n";
                $y -= 22;
            }
            $bottom = $y + 14;
            $x = $tableX;
            $stream .= "0.78 0.81 0.87 RG 0.45 w {$tableX} {$bottom} {$tableWidth} ".(524 - $bottom)." re S\n";
            foreach ($columns as $column) {
                $x += $column['width'];
                $stream .= "{$x} {$bottom} m {$x} 524 l S\n";
            }
            $stream .= $this->text(740, 22, 7, 'F1', '0.4 0.45 0.55', 'Page '.($pageIndex + 1).' of '.count($pages));
            $streams[] = $stream;
        }

        return $this->assemblePdf($streams);
    }

    public function excel(Collection $users): string
    {
        $sheetRows = array_merge([$this->headers()], $this->rows($users));
        $lastColumn = $this->columnName(count($this->headers()));
        $lastColumnNumber = count($this->headers());
        $xmlRows = '';
        foreach ($sheetRows as $rowIndex => $row) {
            $cells = '';
            foreach (array_values($row) as $columnIndex => $value) {
                $reference = $this->columnName($columnIndex + 1).($rowIndex + 1);
                $numeric = is_int($value) || is_float($value);
                $type = $numeric ? 'n' : 'inlineStr';
                $content = $numeric
                    ? '<v>'.$value.'</v>'
                    : '<is><t xml:space="preserve">'.$this->xml((string) $value).'</t></is>';
                $style = $rowIndex === 0 ? ' s="1"' : '';
                $cells .= '<c r="'.$reference.'" t="'.$type.'"'.$style.'>'.$content.'</c>';
            }
            $xmlRows .= '<row r="'.($rowIndex + 1).'">'.$cells.'</row>';
        }

        $temporary = tempnam(sys_get_temp_dir(), 'user-export-');
        $zip = new ZipArchive;
        $zip->open($temporary, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="'.$this->xml($this->sheetName()).'" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
        $zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF3950A2"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/></cellXfs></styleSheet>');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><cols><col min="1" max="1" width="16" customWidth="1"/><col min="2" max="'.$lastColumnNumber.'" width="24" customWidth="1"/></cols><sheetData>'.$xmlRows.'</sheetData><autoFilter ref="A1:'.$lastColumn.count($sheetRows).'"/></worksheet>');
        $zip->close();
        $contents = file_get_contents($temporary);
        @unlink($temporary);

        return $contents === false ? '' : $contents;
    }

    protected function sheetName(): string
    {
        return 'Users';
    }

    protected function text(float $x, float $y, float $size, string $font, string $color, string $text): string
    {
        return "BT /{$font} {$size} Tf {$color} rg {$x} {$y} Td (".$this->escape($this->ascii($text)).") Tj ET\n";
    }

    protected function cellText(float $x, float $y, float $width, float $size, string $font, string $color, string $text): string
    {
        $maxCharacters = max(1, (int) floor(($width - 8) / ($size * 0.6)));

        return $this->text($x + 4, $y, $size, $font, $color, $this->limit($text, $maxCharacters));
    }

    protected function assemblePdf(array $streams): string
    {
        $objects = [1 => '<< /Type /Catalog /Pages 2 0 R >>', 3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>', 4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>'];
        $kids = [];
        foreach ($streams as $index => $stream) {
            $pageId = 5 + ($index * 2);
            $contentId = $pageId + 1;
            $kids[] = "{$pageId} 0 R";
            $objects[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents {$contentId} 0 R >>";
            $objects[$contentId] = '<< /Length '.strlen($stream).">>\nstream\n{$stream}endstream";
        }
        $objects[2] = '<< /Type /Pages /Kids ['.implode(' ', $kids).'] /Count '.count($kids).' >>';
        ksort($objects);
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $id => $object) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$object}\nendobj\n";
        }
        $xref = strlen($pdf);
        $maxId = max(array_keys($objects));
        $pdf .= "xref\n0 ".($maxId + 1)."\n0000000000 65535 f \n";
        for ($id = 1; $id <= $maxId; $id++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$id])."\n";
        }

        return $pdf."trailer\n<< /Size ".($maxId + 1)." /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
    }

    protected function limit(string $value, int $length): string
    {
        return mb_strimwidth($value, 0, $length, '');
    }

    protected function ascii(string $value): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: '';
    }

    protected function escape(string $value): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $value);
    }

    protected function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    protected function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)).$name;
            $number = intdiv($number, 26);
        }

        return $name;
    }
}
