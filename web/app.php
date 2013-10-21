<?php

use carlescliment\Html2Pdf\Application\Application;


require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(__DIR_ . '/../', true);
$app->bindControllers()->run();