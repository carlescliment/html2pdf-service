<?php

namespace Html2Pdf\Test\Functional;

use Silex\WebTestCase;

use carlescliment\Html2Pdf\Application\Application;

class Html2PdfTestCase extends WebTestCase
{

    protected $client;
    private $documentsDir = '/tmp';

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient();
    }


    public function createApplication()
    {
        $app = new Application(__DIR__ .'/../../../../', true);
        $app['documents_dir'] = $this->documentsDir;
        return $app;
    }


    protected function createResource($resource_name)
    {
        $file = fopen($this->getFileFromResource($resource_name), 'w');
        fclose($file);
    }

    protected function deleteResource($resource_name)
    {
        $file_name = $this->getFileFromResource($resource_name);
        if (file_exists($file_name)) {
            unlink($file_name);
        }
    }

    private function getFileFromResource($resource_name)
    {
        return "$this->documentsDir/$resource_name";
    }
}