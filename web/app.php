<?php

use Silex\Application;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application;

$app->get('/', function (Application $app) {
    return $app->json(array('status' => 'ready'));
});

$app->run();