<?php

namespace carlescliment\Html2Pdf\Generator;

use Knp\Snappy\GeneratorInterface;

class PdfGenerator
{

    private $pdfGenerator;
    private $outputDir;

    public function __construct(GeneratorInterface $pdfGenerator,
                                NameGenerator $nameGenerator,
                                $output_dir)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->nameGenerator = $nameGenerator;
        $this->outputDir = $output_dir;
    }

    public function generate($html)
    {
        $file_name = $this->nameGenerator->generate('pdf');
        $this->pdfGenerator->generate($html, $this->outputDir . '/' . $file_name);
        return $file_name;
    }
}