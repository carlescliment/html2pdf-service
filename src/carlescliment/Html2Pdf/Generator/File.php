<?php

namespace carlescliment\Html2Pdf\Generator;

class File
{

    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function toArray()
    {
        return array(
            'location' => $this->location
            );
    }
}