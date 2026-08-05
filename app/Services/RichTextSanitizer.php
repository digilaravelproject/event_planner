<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><blockquote><a>';

    public function sanitize(?string $html): string
    {
        $html = strip_tags((string) $html, self::ALLOWED_TAGS);
        if (trim($html) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8"><div id="page-content">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        $xpath = new DOMXPath($document);
        foreach ($xpath->query('//*[@id="page-content"]//*') ?: [] as $element) {
            if (! $element instanceof DOMElement) {
                continue;
            }

            $href = $element->tagName === 'a' ? $element->getAttribute('href') : '';
            while ($element->attributes->length > 0) {
                $element->removeAttributeNode($element->attributes->item(0));
            }
            if ($element->tagName === 'a' && $this->safeHref($href)) {
                $element->setAttribute('href', $href);
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        $wrapper = $document->getElementById('page-content');
        $clean = '';
        if ($wrapper !== null) {
            foreach ($wrapper->childNodes as $child) {
                $clean .= $document->saveHTML($child);
            }
        }

        return trim($clean);
    }

    private function safeHref(string $href): bool
    {
        return $href !== '' && preg_match('#^(https?://|mailto:|tel:|/|\#)#i', trim($href)) === 1;
    }
}
