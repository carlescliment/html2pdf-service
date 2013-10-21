<?php

namespace carlescliment\Html2Pdf\Application;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;

use carlescliment\Html2Pdf\Generator\PdfGenerator;
use carlescliment\Html2Pdf\Exception\DocumentAlreadyExistsException;
use Knp\Snappy\Pdf;

class Application extends SilexApplication
{
    private $rootDir;


    public function __construct($root_dir, $debug = false)
    {
        parent::__construct();
        $this->rootDir = $root_dir;
        $this->setDebugMode($debug);
        $this->initializeDependencies();
        $this->bindControllers();
    }

    private function setDebugMode($debug)
    {
        if ($debug) {
            $this['exception_handler']->disable();
        }
    }


    private function initializeDependencies()
    {
        $this['documents_dir'] = function(SilexApplication $app) {
            return $this->rootDir . 'web/documents';
        };
        $this['pdf_binary'] = function(SilexApplication $app) {
            return $this->rootDir . 'bin/wkhtmltopdf';
        };
        $this['pdf_generator'] = function(SilexApplication $app) {
            $pdf_maker = new Pdf($app['pdf_binary']);
            $documents_dir = $app['documents_dir'];
            return new PdfGenerator($pdf_maker, $documents_dir);
        };
    }


    private function bindControllers()
    {
        $this->get('/', function (SilexApplication $app) {
            return $app->json(array('status' => 'ready'));
        });

        $this->put('/{file_name}', function (SilexApplication $app, Request $request, $file_name) {
            $content = $request->get('content');
            try {
                $resource_name = $this['pdf_generator']->generate($file_name, $content);
            }
            catch (DocumentAlreadyExistsException $e)
            {
                $error = array('message' => $e->getMessage());
                return $app->json($error, 409);
            }

            return $app->json(array('resource_name' => $resource_name, 'message' => 'ok'));
        });


        $this->delete('/{file_name}', function (SilexApplication $app, $file_name) {
            $full_path = $app['documents_dir'] . '/' . $file_name . '.pdf';
            $code = 404;
            if (file_exists($full_path)) {
                $code = 200;
                unlink($full_path);
            }
            return $app->json(array('message' => 'ok'), $code);
        });

        return $this;
    }

}
