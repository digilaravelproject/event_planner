<?php

namespace App\Services;

use App\Models\UserEventPlan;

class PlanPdfService
{
    public function render(UserEventPlan $plan, array $presentation, bool $includeAudit = false): string
    {
        $blocks = [];
        $blocks[] = ['title', $presentation['title']];
        $blocks[] = ['meta', 'Prepared for '.$plan->user->name.' | '.$plan->guest_count.' guests | '.now()->format('d M Y')];
        $blocks[] = ['body', $presentation['overview']];
        $blocks[] = ['total', 'Estimated plan total: Rs. '.number_format((float) $plan->total_cost, 0)];
        $blocks[] = ['heading', 'YOUR REQUIREMENTS'];
        foreach ($presentation['answers'] as $key => $value) {
            $blocks[] = ['row', str($key)->replace('_', ' ')->title().': '.$this->value($value)];
        }
        $blocks[] = ['heading', 'DETAILED COSTING'];
        foreach ($presentation['costing'] as $item) {
            $blocks[] = ['subheading', ($item['category'] ?? 'Service').' - Rs. '.number_format((float) ($item['amount'] ?? 0), 0)];
            $blocks[] = ['body', (string) ($item['summary'] ?? '')];
            foreach ($item['attributes'] ?? [] as $attribute) {
                $blocks[] = ['row', ($attribute['name'] ?? 'Item').' | '.($attribute['value'] ?? '').' | Rs. '.number_format((float) ($attribute['cost'] ?? 0), 0)];
            }
        }
        $blocks[] = ['heading', 'MATCHED VENDORS'];
        foreach ($presentation['recommendations'] as $vendor) {
            $blocks[] = ['subheading', ($vendor['name'] ?? 'Vendor').' - '.($vendor['category'] ?? 'Service')];
            $blocks[] = ['body', (string) ($vendor['reason'] ?? '')];
            foreach ($vendor['attributes'] ?? [] as $attribute) {
                $blocks[] = ['row', ($attribute['name'] ?? 'Attribute').': '.($attribute['value'] ?? '').' | Indicative Rs. '.number_format((float) ($attribute['cost'] ?? 0), 0)];
            }
        }
        if ($presentation['notes'] !== []) {
            $blocks[] = ['heading', 'PLANNING NOTES'];
            foreach ($presentation['notes'] as $note) {
                $blocks[] = ['row', $note];
            }
        }
        if ($includeAudit) {
            $blocks[] = ['heading', 'AI REQUIREMENT PROMPT - ADMIN AUDIT'];
            $blocks[] = ['body', $plan->requirement_prompt];
            $blocks[] = ['meta', 'Model: '.($plan->model ?: 'Not recorded').' | Status: '.$plan->status];
        }

        return $this->buildPdf($plan, $blocks);
    }

    private function buildPdf(UserEventPlan $plan, array $blocks): string
    {
        $pages = [];
        $commands = $this->pageHeader($plan, 1);
        $y = 790;
        $pageNumber = 1;

        foreach ($blocks as [$type, $text]) {
            $fontSize = match ($type) { 'title' => 21, 'heading' => 12, 'subheading' => 11, 'total' => 14, 'meta' => 8, default => 9 };
            $leading = $fontSize + 4;
            $maxChars = match ($type) { 'title' => 42, 'heading' => 55, 'subheading' => 66, default => 82 };
            $lines = $this->wrap($this->ascii((string) $text), $maxChars);
            $required = count($lines) * $leading + (in_array($type, ['heading', 'subheading'], true) ? 8 : 4);
            if ($y - $required < 55) {
                $commands .= $this->footer($pageNumber);
                $pages[] = $commands;
                $pageNumber++;
                $commands = $this->pageHeader($plan, $pageNumber);
                $y = 790;
            }

            if ($type === 'heading') {
                $y -= 8;
                $commands .= "0.52 0.02 0.14 rg 150 ".($y - 3)." 405 20 re f\n";
            }
            $color = match ($type) { 'heading' => '1 1 1', 'total', 'subheading' => '0.52 0.02 0.14', 'meta' => '0.38 0.42 0.48', default => '0.10 0.13 0.20' };
            $font = in_array($type, ['title', 'heading', 'subheading', 'total'], true) ? 'F2' : 'F1';
            foreach ($lines as $line) {
                $commands .= "BT /{$font} {$fontSize} Tf {$color} rg 155 {$y} Td (".$this->escape($line).") Tj ET\n";
                $y -= $leading;
            }
            $y -= in_array($type, ['heading', 'subheading', 'total'], true) ? 7 : 3;
        }
        $commands .= $this->footer($pageNumber);
        $pages[] = $commands;

        return $this->assemble($pages);
    }

    private function pageHeader(UserEventPlan $plan, int $page): string
    {
        $total = number_format((float) $plan->total_cost, 0);
        return "0.98 0.97 0.95 rg 0 0 595 842 re f\n"
            ."0.52 0.02 0.14 rg 0 0 130 842 re f\n"
            ."0.83 0.67 0.18 rg 18 792 94 2 re f\n"
            ."BT /F2 13 Tf 1 1 1 rg 18 765 Td (SHAADI SENSE) Tj ET\n"
            ."BT /F1 9 Tf 0.95 0.82 0.45 rg 18 746 Td (AI WEDDING PLAN) Tj ET\n"
            ."BT /F2 11 Tf 1 1 1 rg 18 690 Td (PLAN SNAPSHOT) Tj ET\n"
            ."BT /F1 9 Tf 1 1 1 rg 18 665 Td (Guests: ".$plan->guest_count.") Tj ET\n"
            ."BT /F1 9 Tf 1 1 1 rg 18 645 Td (Budget:) Tj ET\n"
            ."BT /F2 9 Tf 1 1 1 rg 18 627 Td (Rs. {$total}) Tj ET\n"
            ."BT /F1 9 Tf 1 1 1 rg 18 607 Td (Page: {$page}) Tj ET\n"
            ."0.83 0.67 0.18 rg 18 588 94 1 re f\n";
    }

    private function footer(int $page): string
    {
        return "0.75 0.75 0.75 rg 150 35 405 0.5 re f\nBT /F1 7 Tf 0.4 0.4 0.4 rg 155 22 Td (Indicative plan - confirm final prices and availability with vendors. Page {$page}) Tj ET\n";
    }

    private function assemble(array $pageStreams): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
        ];
        $kids = [];
        foreach ($pageStreams as $index => $stream) {
            $pageId = 5 + ($index * 2);
            $contentId = $pageId + 1;
            $kids[] = "{$pageId} 0 R";
            $objects[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents {$contentId} 0 R >>";
            $objects[$contentId] = "<< /Length ".strlen($stream)." >>\nstream\n{$stream}endstream";
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

    private function wrap(string $text, int $width): array
    {
        $text = preg_replace('/\s+/', ' ', trim($text)) ?: '';
        return $text === '' ? [''] : explode("\n", wordwrap($text, $width, "\n", true));
    }

    private function ascii(string $text): string
    {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        return $converted === false ? preg_replace('/[^\x20-\x7E]/', '', $text) : $converted;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private function value(mixed $value): string
    {
        if (is_array($value)) {
            return collect($value)->flatten()->implode(', ');
        }
        if (is_string($value) && str_starts_with($value, '[')) {
            return implode(', ', json_decode($value, true) ?: [$value]);
        }
        return (string) $value;
    }
}
