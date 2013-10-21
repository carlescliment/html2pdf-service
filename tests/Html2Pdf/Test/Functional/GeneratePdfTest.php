<?php

namespace Html2Pdf\Test\Functional;

use Silex\WebTestCase,
    Silex\Application as SilexApplication;

use carlescliment\Html2Pdf\Application\Application;

class GeneratePdfTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient();
        $this->deleteFixtures();
    }


    private function deleteFixtures()
    {
        $file_name = '/tmp/output.pdf';
        if (file_exists($file_name)) {
            unlink($file_name);
        }
    }

    public function createApplication()
    {
        $app = new Application(__DIR__ .'/../../../../', true);
        $app['documents_dir'] = '/tmp';
        return $app;
    }


    /**
     * @test
     */
    public function itReturnsTheResourceLocationWhenCreatingAFile()
    {
        $this->requestFileCreation();

        $this->assertLocationIsProviden();
    }


    /**
     * @test
     */
    public function itReturnsAResponseErrorWhenTheResourceAlreadyExists()
    {
        $this->createFile('output.pdf');

        $this->requestFileCreation();

        $this->assertAlreadyExistingErrorIsReturned();
    }


    private function requestFileCreation($file_name = 'output')
    {
        $data = array('content' => '<html><head></head><body>Some html</body></html>');
        $this->client->request('PUT', "/$file_name", $data);
    }


    private function assertLocationIsProviden()
    {
        $response = $this->client->getResponse();
        $decoded = json_decode($response->getContent());
        $this->assertTrue(isset($decoded->resource_name));
        $this->assertEquals(200, $response->getStatusCode());
    }


    private function createFile($file_name)
    {
        $file = fopen('/tmp/' . $file_name, 'w');
        fclose($file);
    }

    private function assertAlreadyExistingErrorIsReturned()
    {
        $response = $this->client->getResponse();
        $this->assertEquals(409, $response->getStatusCode());
    }
}