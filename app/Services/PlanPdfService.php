<?php

namespace App\Services;

use App\Models\UserEventPlan;

class PlanPdfService
{
    public function render(UserEventPlan $plan, array $presentation): string
    {
        $pages = [];
        $page = 1;
        $commands = $this->pageHeader($plan, $page);
        $y = 748;
        $ensure = function (float $height) use (&$pages, &$page, &$commands, &$y, $plan): void {
            if ($y - $height >= 64) {
                return;
            }
            $pages[] = $commands.$this->footer($page++);
            $commands = $this->pageHeader($plan, $page);
            $y = 748;
        };
        $paragraph = function (string $text, int $size = 10, string $font = 'F1', string $color = '0.30 0.34 0.40') use (&$commands, &$y, $ensure): void {
            foreach ($this->wrap($this->ascii($text), 500, $size) as $line) {
                $ensure($size + 8);
                $commands .= $this->text(42, $y, $line, $size, $font, $color);
                $y -= $size + 6;
            }
            $y -= 6;
        };
        $paragraph('YOUR CELEBRATION, BEAUTIFULLY PLANNED', 9, 'F2', '0.55 0.39 0.12');
        $paragraph($presentation['title'], 26, 'F3', '0.28 0.03 0.10');
        $paragraph('Prepared for '.$plan->user->name.'  |  Plan #'.$plan->id.'  |  '.($plan->created_at ?? now())->format('d M Y'), 9);
        $paragraph($presentation['overview']);
        $ensure(90);
        $commands .= '0.52 0.02 0.14 rg 42 '.($y - 75)." 511 75 re f\n";
        $commands .= $this->text(58, $y - 21, 'PRICED PLAN TOTAL', 9, 'F2', '0.96 0.83 0.52');
        $commands .= $this->text(58, $y - 52, 'Rs. '.number_format($plan->total_cost, 2), 24, 'F2', '1 1 1');
        $commands .= $this->text(388, $y - 25, number_format($plan->guest_count).' GUESTS', 10, 'F2', '1 1 1');
        $commands .= $this->text(388, $y - 46, count($presentation['costing']).' SERVICE CATEGORIES', 8, 'F1', '1 0.89 0.91');
        $y -= 100;
        $paragraph('DETAILED COSTING', 13, 'F2', '0.52 0.02 0.14');
        foreach ($presentation['costing'] as $index => $item) {
            $ensure(115);
            $paragraph(sprintf('%02d', $index + 1).'  '.$item['category'], 17, 'F3', '0.28 0.03 0.10');
            $paragraph('Category subtotal: Rs. '.number_format($item['amount'], 2).(($item['pricing_status'] ?? '') === 'quote_required' ? '  |  Additional quote required' : ''), 9, 'F2');
            if (! empty($item['cost_warning'])) {
                $paragraph($item['cost_warning'], 9);
            }
            $rows = $item['attributes'] ?: [['name' => 'Quote required', 'value' => 'No itemized vendor rates saved for this category.', 'pricing_status' => 'quote_required', 'cost' => 0]];
            foreach ($rows as $row) {
                $details = array_merge(
                    $this->wrap($this->ascii($row['name']), 265, 10),
                    empty($row['vendor_name']) ? [] : $this->wrap($this->ascii($row['vendor_name']), 265, 9),
                    empty($row['value']) ? [] : $this->wrap($this->ascii($row['value']), 265, 9)
                );
                $overflowDetails = array_slice($details, 24);
                $details = array_slice($details, 0, 24);
                $height = max(61, count($details) * 13 + 22);
                $ensure($height + 10);
                $commands .= '1 1 1 rg 42 '.($y - $height)." 511 {$height} re f\n";
                $commands .= '0.89 0.84 0.81 RG 42 '.($y - $height)." 511 {$height} re S\n";
                foreach ($details as $lineIndex => $line) {
                    $commands .= $this->text(54, $y - 18 - $lineIndex * 13, $line, $lineIndex === 0 ? 10 : 9, $lineIndex === 0 ? 'F2' : 'F1');
                }
                $commands .= $this->text(335, $y - 17, 'RATE / QUANTITY', 7, 'F2', '0.5 0.5 0.5');
                if (isset($row['unit_price'])) {
                    $commands .= $this->text(335, $y - 33, 'Rs. '.number_format($row['unit_price'], 2), 9);
                    $commands .= $this->text(335, $y - 47, 'x '.($row['quantity'] ?? '?').' '.str_replace('per_', '', $row['unit'] ?? 'service'), 8);
                } else {
                    $commands .= $this->text(335, $y - 33, 'Saved estimate', 8);
                }
                $commands .= $this->text(448, $y - 17, 'AMOUNT (INR)', 7, 'F2', '0.5 0.5 0.5');
                $commands .= $this->text(448, $y - 34, ($row['pricing_status'] ?? '') === 'quote_required' ? 'Quote required' : number_format($row['cost'], 2), 9, 'F2', '0.52 0.02 0.14');
                $y -= $height + 8;
                if ($overflowDetails !== []) {
                    $paragraph(implode(' ', $overflowDetails), 9);
                }
            }
            $y -= 14;
        }
        if (! empty($presentation['answer_details'])) {
            $ensure(80);
            $paragraph('YOUR EVENT REQUIREMENTS', 13, 'F2', '0.52 0.02 0.14');
            foreach ($presentation['answer_details'] as $answer) {
                $ensure(65);
                $paragraph($answer['question'], 10, 'F2');
                $paragraph($answer['answer'], 9);
            }
        }
        $ensure(85);
        $paragraph('BEFORE YOU BOOK', 13, 'F2', '0.52 0.02 0.14');
        foreach (array_unique(array_merge($presentation['notes'] ?? [], ['Saved rates are indicative, not live quotations. Confirm scope, taxes and final prices with each vendor. Unpriced services are excluded from the total.'])) as $note) {
            $paragraph($note, 9);
        }
        $pages[] = $commands.$this->footer($page);

        return $this->assemble($pages);
    }

    private function pageHeader(UserEventPlan $plan, int $page): string
    {
        return "0.98 0.97 0.95 rg 0 0 595 842 re f\n"
            ."0.52 0.02 0.14 rg 0 780 595 62 re f\n"
            ."0.83 0.67 0.18 rg 42 779 511 2 re f\n"
            .$this->text(42, 805, 'SHAADI SENSE', 19, 'F3', '1 1 1')
            .$this->text(366, 806, 'YOUR PERSONAL EVENT BLUEPRINT', 8, 'F2', '0.95 0.82 0.45');
    }

    private function footer(int $page): string
    {
        return "0.83 0.67 0.18 rg 42 43 511 0.5 re f\n"
            .$this->text(42, 27, 'SHAADI SENSE  /  Carefully planned. Beautifully celebrated.', 8)
            .$this->text(505, 27, 'PAGE '.$page, 8, 'F2');
    }

    private function text(float $x, float $y, string $text, int $size = 9, string $font = 'F1', string $color = '0.16 0.19 0.24'): string
    {
        return "BT /{$font} {$size} Tf {$color} rg {$x} {$y} Td (".$this->escape($this->ascii($text)).") Tj ET\n";
    }

    private function assemble(array $pageStreams): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
            5 => '<< /Type /Font /Subtype /Type1 /BaseFont /Times-Bold >>',
        ];
        $kids = [];
        foreach ($pageStreams as $index => $stream) {
            $pageId = 6 + ($index * 2);
            $contentId = $pageId + 1;
            $kids[] = "{$pageId} 0 R";
            $objects[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R /F2 4 0 R /F3 5 0 R >> >> /Contents {$contentId} 0 R >>";
            $objects[$contentId] = '<< /Length '.strlen($stream)." >>\nstream\n{$stream}endstream";
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
        $pdf .= "trailer\n<< /Size ".($maxId + 1)." /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
    }

    private function wrap(string $text, int $width, int $size): array
    {
        $text = preg_replace('/\s+/', ' ', trim($text)) ?: '';
        $lines = [];
        $line = '';
        $length = 0;
        foreach (str_split($text) as $character) {
            // Conservative widths cover all three built-in PDF fonts, including bold.
            $factor = match (true) {
                str_contains('WMwm@%', $character) => 1.0,
                str_contains('ilI.,:;!|\' ', $character) => .34,
                ctype_upper($character) => .8,
                default => .65,
            };
            if ($length + $factor * $size > $width && $line !== '') {
                $space = strrpos($line, ' ');
                $rest = $space === false ? '' : substr($line, $space + 1);
                $lines[] = $space === false ? $line : substr($line, 0, $space);
                $line = $rest;
                $length = strlen($rest) * $size; // safe upper bound for carried word
            }
            $line .= $character;
            $length += $factor * $size;
        }
        if ($line !== '' || $lines === []) {
            $lines[] = trim($line);
        }

        return $lines;
    }

    private function ascii(string $text): string
    {
        $text = str_replace('₹', 'Rs. ', $text);
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        return $converted === false ? preg_replace('/[^\x20-\x7E]/', '', $text) : $converted;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
