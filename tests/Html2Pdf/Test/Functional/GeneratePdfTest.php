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
    }

    public function createApplication()
    {
        $app = new Application(true);
        $app->bindControllers();
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


    private function requestFileCreation()
    {
        $data = array('content' => '<html><head></head><body>Some html</body></html>');
        $this->client->request('POST', "/author", $data);
    }


    private function assertLocationIsProviden()
    {
        $response = $this->client->getResponse();
        $decoded = json_decode($response->getContent());
        $this->assertTrue(isset($decoded->location));
    }
}