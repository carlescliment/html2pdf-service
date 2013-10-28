<?php

namespace Html2Pdf\Test\Generator;

use carlescliment\Html2Pdf\Generator\PdfGenerator;

class PdfGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $pdfGenerator;
    private $generator;

    public function setUp() {
        $this->pdfGenerator = $this->getMock('Knp\Snappy\GeneratorInterface');
        $this->configurator = $this->getMock('carlescliment\Html2Pdf\Generator\Configuration');
        $this->generator = new PdfGenerator($this->pdfGenerator, $this->configurator, '/some/dir');
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
    public function itSetsTheDocumentOptions()
    {
        $options = array('encoding' => 'UTF-8');

        $this->configurator->expects($this->once())
            ->method('configure')
            ->with($this->pdfGenerator, $options);

        $this->generator->generate('filename', '', $options);
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