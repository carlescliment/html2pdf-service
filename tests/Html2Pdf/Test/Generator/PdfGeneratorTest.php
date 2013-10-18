<?php

namespace Html2Pdf\Test\Generator;

use carlescliment\Html2Pdf\Generator\PdfGenerator;

class PdfGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itReturnsAFileInstance()
    {
        $generator = new PdfGenerator;

        $file = $generator->generate();

        $this->assertEquals('carlescliment\Html2Pdf\Generator\File', get_class($file));
    }
}