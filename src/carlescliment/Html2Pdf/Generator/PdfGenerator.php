<?php

namespace carlescliment\Html2Pdf\Generator;

use Knp\Snappy\GeneratorInterface;

class PdfGenerator
{

    private $pdfGenerator;
    private $outputDir;

    public function __construct(GeneratorInterface $pdfGenerator, $output_dir)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->outputDir = $output_dir;
    }

    public function generate($file_name, $html)
    {
        $full_name = "$file_name.pdf";
        $this->pdfGenerator->generateFromHtml($html, $this->outputDir . '/' . $full_name);
        return $full_name;
    }
}