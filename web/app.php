<?php

use carlescliment\Html2Pdf\Application\Application;


require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(__DIR__ . '/../', false);
$app->run();
