<?php

namespace Html2Pdf\Test\Generator;

use carlescliment\Html2Pdf\Generator\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itConvertsItselfIntoAnArray()
    {
        $location = '/the/location/to/file';
        $file = new File($location);
        $expected = array('location' => $location);

        $actual = $file->toArray();

        $this->assertEquals($expected, $actual);
    }
}