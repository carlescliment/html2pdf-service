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

    protected function deleteResourceIfExists($resource_name)
    {
        $file_name = $this->getFileFromResource($resource_name);
        if (file_exists($file_name)) {
            unlink($file_name);
        }
    }


    protected function assertResourceExists($resource_name)
    {
        $file_exists = file_exists($this->getFileFromResource($resource_name));
        $this->assertTrue($file_exists);
    }


    protected function assertResourceDoesNotExist($resource_name)
    {
        $file_exists = file_exists($this->getFileFromResource($resource_name));
        $this->assertFalse($file_exists);
    }


    protected function assertResponseIsSuccessful()
    {
        $this->assertResponseCode(200);
    }


    protected function assertResponseIsNotFound()
    {
        $this->assertResponseCode(404);
    }


    protected function assertResponseIsConflict()
    {
        $this->assertResponseCode(409);
    }

    protected function assertResponseCode($code)
    {
        $response = $this->client->getResponse();
        $this->assertEquals($code, $response->getStatusCode());
    }


    private function getFileFromResource($resource_name)
    {
        return "$this->documentsDir/$resource_name.pdf";
    }
}