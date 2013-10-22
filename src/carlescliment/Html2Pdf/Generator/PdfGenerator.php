<?php

namespace carlescliment\Html2Pdf\Generator;

use Knp\Snappy\GeneratorInterface;
use carlescliment\Html2Pdf\Exception\DocumentAlreadyExistsException;

class PdfGenerator
{

    private $pdfGenerator;
    private $outputDir;

    public function __construct(GeneratorInterface $pdfGenerator, $output_dir)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->outputDir = $output_dir;
    }

    public function generate($resource_name, $html)
    {
        $document_path = $this->fullDocumentPath($resource_name);
        $this->tryToGenerateDocument($html, $document_path);
        return $document_path;
    }


    private function tryToGenerateDocument($html, $document_path)
    {
        try {
            $this->pdfGenerator->generateFromHtml($html, $document_path);
        }
        catch (\InvalidArgumentException $e) {
            throw new DocumentAlreadyExistsException('The resource already exists');
        }
    }

    private function fullDocumentPath($resource_name)
    {
        return "$this->outputDir/$resource_name.pdf";
    }
}
