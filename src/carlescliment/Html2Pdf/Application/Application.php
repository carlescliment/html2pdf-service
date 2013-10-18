<?php

namespace carlescliment\Html2Pdf\Application;

use Silex\Application as SilexApplication;


class Application extends SilexApplication
{

    public function __construct($debug = false)
    {
        parent::__construct();
        if ($debug) {
            $this['exception_handler']->disable();
        }
    }


    public function bindControllers()
    {

        $this->get('/', function (SilexApplication $app) {
            return $app->json(array('status' => 'ready'));
        });

        $this->post('/{author}', function (SilexApplication $app, $author) {
            return $app->json(array('author' => $author));;
        });

        return $this;
    }
}
