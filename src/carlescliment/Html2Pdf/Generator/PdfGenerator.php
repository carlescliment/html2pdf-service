<?php

namespace carlescliment\Html2Pdf\Generator;

use Knp\Snappy\GeneratorInterface;
use carlescliment\Html2Pdf\Exception\DocumentAlreadyExistsException;

class PdfGenerator
{

    private $pdfGenerator;
    private $outputDir;
    private $publicPath;

    public function __construct(GeneratorInterface $pdfGenerator, $output_dir, $public_path)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->outputDir = $output_dir;
        $this->publicPath = $public_path;
    }

    public function generate($resource_name, $html)
    {
        $document_path = $this->privatePath($resource_name);
        $this->tryToGenerateDocument($html, $document_path);
        return $this->publicPath($resource_name);
    }


    private function tryToGenerateDocument($html, $document_path)
    {
        try {
            $this->pdfGenerator->generateFromHtml($html, $document_path);
        }
        catch (\InvalidArgumentException $e) {
            throw new DocumentAlreadyExistsException($e->getMessage());
            throw new DocumentAlreadyExistsException('The resource already exists');
        }
    }

    private function privatePath($resource_name)
    {
        return "$this->outputDir/$resource_name.pdf";
    }

    private function publicPath($resource_name)
    {
        return "$this->publicPath/$resource_name.pdf";
    }
}
