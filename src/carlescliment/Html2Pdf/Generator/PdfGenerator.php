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


    public function generate($resource_name, $html, array $options = array())
    {
        $document_path = $this->filePath($resource_name);
        try {
            $this->pdfGenerator->generateFromHtml($html, $document_path, $options);
        }
        catch (\InvalidArgumentException $e) {
            throw new DocumentAlreadyExistsException($e->getMessage());
        }
    }


    private function filePath($resource_name)
    {
        return "$this->outputDir/$resource_name.pdf";
    }
}
