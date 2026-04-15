<?php

namespace App\Services;

use App\Helpers\HtmlToOoxml;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Extends PhpWord's TemplateProcessor to support injecting HTML-formatted content
 * as proper OOXML (Word formatted text) into .docx template placeholders.
 * Also supports LaTeX → OMML math equation conversion.
 */
class RichTemplateProcessor extends TemplateProcessor
{
    /**
     * Replace a ${placeholder} with HTML-formatted content rendered as OOXML.
     * Supports: bold, italic, underline, color, highlight/shading, font-size, alignment,
     *           indent, superscript, subscript, numbered/bullet lists, LaTeX math formulas.
     */
    public function setHtmlValue(string $search, string $html): void
    {
        // Step 1: Reduce placeholder to a unique, simple string inside the XML
        $unique = 'HTMLREPLACE_' . strtoupper(substr(md5($search . microtime()), 0, 12));
        $this->setValue($search, $unique);

        // Step 2: Convert HTML to OOXML paragraphs (including LaTeX → OMML)
        $ooxml = HtmlToOoxml::convert($html);

        // Step 3: Ensure OMML math namespace is declared if math elements are present
        if (str_contains($ooxml, '<m:oMath')) {
            $this->ensureMathNamespace();
        }

        // Step 4: Replace the entire <w:p> block containing our marker with the OOXML
        $this->tempDocumentMainPart = preg_replace(
            '/<w:p\b[^>]*>(?:(?!<\/w:p>).)*?' . preg_quote($unique, '/') . '.*?<\/w:p>/s',
            $ooxml,
            $this->tempDocumentMainPart
        );
    }

    /**
     * Ensure the OMML math namespace (xmlns:m) is declared in the document root element.
     * Without this, Word cannot interpret <m:oMath> elements.
     */
    private function ensureMathNamespace(): void
    {
        $mathNs = 'xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"';

        // Only add if not already present
        if (!str_contains($this->tempDocumentMainPart, $mathNs)) {
            // Insert the namespace declaration into the root <w:document> tag
            $this->tempDocumentMainPart = preg_replace(
                '/<w:document\b/',
                '<w:document ' . $mathNs,
                $this->tempDocumentMainPart,
                1
            );
        }
    }
}
