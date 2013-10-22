<?php

namespace Html2Pdf\Test\Generator;

use carlescliment\Html2Pdf\Generator\PdfGenerator;

class PdfGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $pdfGenerator;
    private $generator;
    private $outputDir;

    public function setUp() {
        $this->pdfGenerator = $this->getMock('Knp\Snappy\GeneratorInterface');
        $this->outputDir = '/some/dir';
        $this->generator = new PdfGenerator($this->pdfGenerator, $this->outputDir);
    }


    /**
     * @test
     */
    public function itGeneratesTheGivenHtml()
    {
        $html = '<html><body>foo</body></html>';

        $this->pdfGenerator->expects($this->once())
            ->method('generateFromHtml')
            ->with($html);

        $this->generator->generate('filename', $html);
    }


    /**
     * @test
     */
    public function itPutsTheFileInTheSpecifiedFolder()
    {
        $this->pdfGenerator->expects($this->once())
            ->method('generateFromHtml')
            ->with(null, '/some/dir/filename.pdf');

        $this->generator->generate('filename', null);
    }


    /**
     * @test
     */
    public function itReturnsTheResourceLocation()
    {
        $file_name = $this->generator->generate('file_name', null);

        $this->assertEquals('/some/dir/file_name.pdf', $file_name);
    }


    /**
     * @test
     * @expectedException carlescliment\Html2Pdf\Exception\DocumentAlreadyExistsException
     */
    public function itThrowsAnExceptionIfFileAlreadyExists()
    {
        $this->pdfGenerator->expects($this->any())
            ->method('generateFromHtml')
            ->will($this->throwException(new \InvalidArgumentException));

        $this->generator->generate('file_name', null);
    }
}