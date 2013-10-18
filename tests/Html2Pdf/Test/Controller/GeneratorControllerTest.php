<?php

namespace Html2Pdf\Test\Controller;

use carlescliment\Html2Pdf\Controller\GeneratorController;

class GeneratorControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itWorks()
    {
        $generator = new GeneratorController;

        $output = $generator->generate();

        $this->assertEquals('It works!', $output);
    }
}