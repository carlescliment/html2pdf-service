<?php

namespace Html2Pdf\Test\Generator;

use carlescliment\Html2Pdf\Generator\PdfGenerator;

class PdfGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $pdfGenerator;
    private $nameGenerator;
    private $generator;
    private $outputDir;

    public function setUp() {
        $this->pdfGenerator = $this->getMock('Knp\Snappy\GeneratorInterface');
        $this->outputDir = '/some/dir';
        $this->nameGenerator = $this->getMock('carlescliment\Html2Pdf\Generator\NameGenerator');
        $this->generator = new PdfGenerator($this->pdfGenerator, $this->nameGenerator, $this->outputDir);
    }


    /**
     * @test
     */
    public function itGeneratesTheGivenHtml()
    {
        $html = '<html><body>foo</body></html>';

        $this->pdfGenerator->expects($this->once())
            ->method('generate')
            ->with($html);

        $this->generator->generate($html);
    }


    /**
     * @test
     */
    public function itGeneratesANameForTheOutputFile()
    {
        $this->nameGenerator->expects($this->once())
            ->method('generate')
            ->with('pdf');

        $this->generator->generate(null);
    }


    /**
     * @test
     */
    public function itPutsTheFileInTheSpecifiedFolder()
    {
        $this->stubGeneratedFileName('some_file_name.pdf');

        $this->pdfGenerator->expects($this->once())
            ->method('generate')
            ->with(null, '/some/dir/some_file_name.pdf');

        $this->generator->generate(null);
    }

    /**
     * @test
     */
    public function itReturnsTheOutputFileName()
    {
        $this->stubGeneratedFileName('some_file_name.pdf');

        $file_name = $this->generator->generate('');

        $this->assertEquals('some_file_name.pdf', $file_name);
    }


    private function stubGeneratedFileName($generated_file_name)
    {
        $this->nameGenerator->expects($this->any())
            ->method('generate')
            ->will($this->returnValue($generated_file_name));
    }

}