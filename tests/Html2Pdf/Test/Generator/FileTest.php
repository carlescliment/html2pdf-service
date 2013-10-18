<?php

namespace Html2Pdf\Test\Generator;

use carlescliment\Html2Pdf\Generator\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itHasALocation()
    {
        $location = '/the/location/to/file';

        $file = new File($location);

        $this->assertEquals($location, $file->location());
    }
}