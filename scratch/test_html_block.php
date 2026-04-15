<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

$templatePath = 'storage/app/templates/template_notulen.docx';
if (file_exists($templatePath)) {
    $templateProcessor = new TemplateProcessor($templatePath);
    if (method_exists($templateProcessor, 'setHtmlBlockValue')) {
        echo "setHtmlBlockValue exists.\n";
    } else {
        echo "setHtmlBlockValue DOES NOT exist.\n";
    }
}
