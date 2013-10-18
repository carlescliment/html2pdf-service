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
    public function itBringsARouteToCreatePDFFilesByAuthor()
    {
        $author = 'chuck';

        $this->client->request('POST', "/$author");

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}