<?php

namespace carlescliment\Html2Pdf\Application;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;

use carlescliment\Html2Pdf\Generator\PdfGenerator,
    carlescliment\Html2Pdf\Generator\NameGenerator;
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

        $this->post('/', function (SilexApplication $app, Request $request) {
            $content = $request->get('content');

            $resource_name = $this['pdf_generator']->generate($content);

            return $app->json(array('resource_name' => $resource_name));
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
            $name_generator = new NameGenerator;
            $pdf_maker = new Pdf($app['pdf_binary']);
            $documents_dir = $app['documents_dir'];
            return new PdfGenerator($pdf_maker, $name_generator, $documents_dir);
        };
    }


    private function getRootDir()
    {
        return __DIR__ . '/../../../../';
    }
}
