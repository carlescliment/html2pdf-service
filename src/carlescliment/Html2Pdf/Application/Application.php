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
        $this['default_options'] = function() {
            return array(
                'page-size' => 'A4',
                'encoding' => 'UTF-8',
                );
        };

        $this['documents_dir'] = function(SilexApplication $app) {
            return $app->getRootDir() . 'documents';
        };

        $this['pdf_binary'] = function(SilexApplication $app) {
            return $app->getRootDir() . 'bin/wkhtmltopdf';
        };

        $this['pdf_generator'] = function(SilexApplication $app) {
            $pdf_maker = new Pdf($app['pdf_binary'], $app['default_options']);
            $documents_dir = $app['documents_dir'];
            return new PdfGenerator($pdf_maker, $documents_dir);
        };
    }


    private function bindControllers()
    {
        $this->get('/{resource}', function (SilexApplication $app, $resource) {
            $file = $app->getFileFromResource($resource);
            if (!file_exists($file)) {
                return $app->json(array('body' => 'Not found'), 404);
            }
            $contents = file_get_contents($file);
            return $app->json(array('body' => base64_encode($contents), 'encoding' => 'base64'));
        });


        $this->put('/{resource}', function (SilexApplication $app, Request $request, $resource) {
            $content = $request->get('content');
            try {
                $app['pdf_generator']->generate($resource, $content);
            }
            catch (DocumentAlreadyExistsException $e)
            {
                return $app->json(array('body' => $e->getMessage()), 409);
            }
            return $app->json(array('body' => 'ok'));
        });


        $this->delete('/{resource}', function (SilexApplication $app, $resource) {
            $file = $app->getFileFromResource($resource);
            $code = 404;
            if (file_exists($file)) {
                $code = 200;
                unlink($file);
            }
            return $app->json(array('body' => 'ok'), $code);
        });

        return $this;
    }


    public function getFileFromResource($resource)
    {
        return $this['documents_dir'] . '/' . $resource .'.pdf';
    }


    public function getRootDir()
    {
        return $this->rootDir;
    }
}
