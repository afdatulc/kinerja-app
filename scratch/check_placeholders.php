<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

$templatePath = 'storage/app/templates/template_notulen.docx';
if (file_exists($templatePath)) {
    $templateProcessor = new TemplateProcessor($templatePath);
    echo "Placeholders found in template:\n";
    print_r($templateProcessor->getVariables());
} else {
    echo "Template not found at $templatePath\n";
}
