<?php

namespace carlescliment\Html2Pdf\Application;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;

use carlescliment\Html2Pdf\Generator\PdfGenerator;

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

        $this->post('/{author}', function (SilexApplication $app, Request $request, $author) {
            $generator = new PdfGenerator;

            $resource = $generator->generate($author, $request->get('content'));

            return $app->json(array('location' => $resource->location()));
        });

        return $this;
    }
}
