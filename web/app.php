<?php

use carlescliment\Html2Pdf\Application\Application;


require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(true);
$app->bindControllers()->run();