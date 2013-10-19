<?php

namespace carlescliment\Html2Pdf\Application;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;

use carlescliment\Html2Pdf\Generator\PdfGenerator;
use Knp\Snappy\Pdf;

class Application extends SilexApplication
{

    public function __construct($debug = false)
    {
        parent::__construct();
        if ($debug) {
            $this['exception_handler']->disable();
        }
        $this->initializeDependencies();
    }

    public function bindControllers()
    {

        $this->get('/', function (SilexApplication $app) {
            return $app->json(array('status' => 'ready'));
        });

        $this->post('/{author}', function (SilexApplication $app, Request $request, $author) {
            $content = $request->get('content');

            $resource = $this['pdf_generator']->generate($author, $content);

            return $app->json($resource->toArray());
        });

        return $this;
    }


    private function initializeDependencies()
    {
        $this['root_dir'] = $this->getRootDir();
        $this['documents_dir'] = function(SilexApplication $app) {
            return $app['root_dir'] . 'documents';
        };
        $this['pdf_binary'] = function(SilexApplication $app) {
            return $app['root_dir'] . 'bin/wkhtmltopdf';
        };
        $this['pdf_generator'] = function(SilexApplication $app) {
            return new PdfGenerator(new Pdf($app['pdf_binary']));
        };
    }


    private function getRootDir()
    {
        return __DIR__ . '/../../../../';
    }
}
