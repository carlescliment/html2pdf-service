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
        $this['root_dir'] = $this->rootDir;

        $this['default_options'] = array(
            'page-size' => 'A4',
            'encoding' => 'UTF-8',
            );

        $this['documents_dir'] = $this->rootDir . 'documents' ;

        $this['platform'] = 'linux-amd64';

        $this['pdf_binary'] = function(SilexApplication $app) {
            $binaries = array(
                'linux-amd64' => 'wkhtmltopdf-linux-amd64',
                'linux-i368' => 'wkhtmltopdf-linux-i368',
                'mac' => 'wkhtmltopdf-mac',
                );
            if (!isset($binaries[$app['platform']])) {
                $message = sprintf('No binaries are configured for the selected platform [%s]', $app['platform']);
                throw new \Exception($message);
            }
            return $app['root_dir'] . 'bin' . DIRECTORY_SEPARATOR . $binaries[$app['platform']];
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
            $options = array('footer-left' => $request->get('footer-left'));
            try {
                $app['pdf_generator']->generate($resource, $content, $options);
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

}
