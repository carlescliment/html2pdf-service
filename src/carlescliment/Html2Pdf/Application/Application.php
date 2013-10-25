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
            return $this->rootDir . 'documents';
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
        $this->get('/{resource}', function (SilexApplication $app, $resource) {
            return $app->sendFile($app->getFileNameFromResource($resource));
        });

        $this->put('/{resource}', function (SilexApplication $app, Request $request, $resource) {
            $content = $request->get('content');
            try {
                $resource_name = $this['pdf_generator']->generate($resource, $content);
            }
            catch (DocumentAlreadyExistsException $e)
            {
                $error = array('message' => $e->getMessage());
                return $app->json($error, 409);
            }

            return $app->json(array('resource_name' => $resource_name, 'message' => 'ok'));
        });


        $this->delete('/{resource}', function (SilexApplication $app, $resource) {
            $full_path = $app->getFileNameFromResource($resource);
            $code = 404;
            if (file_exists($full_path)) {
                $code = 200;
                unlink($full_path);
            }
            return $app->json(array('message' => 'ok'), $code);
        });

        return $this;
    }


    public function getFileNameFromResource($resource)
    {
        return $this['documents_dir'] . '/' . $resource .'.pdf';
    }

}
