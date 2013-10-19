<?php

namespace carlescliment\Html2Pdf\Generator;

class NameGenerator
{
    public function generate($extension) {
        return "output.$extension";
    }
}