<?php

namespace Html2Pdf\Test\Functional;

class GeneratePdfTest extends Html2PdfTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->deleteResource('output');
    }

    /**
     * @test
     */
    public function itReturnsTheResourceLocationWhenCreatingAResource()
    {
        $this->requestResourceCreation();

        $this->assertLocationIsProviden();
    }


    /**
     * @test
     */
    public function itReturnsAResponseErrorWhenTheResourceAlreadyExists()
    {
        $this->createResource('output');

        $this->requestResourceCreation('output');

        $this->assertAlreadyExistingErrorIsReturned();
    }


    private function requestResourceCreation($resource_name = 'output')
    {
        $data = array('content' => '<html><head></head><body>Some html</body></html>');
        $this->client->request('PUT', "/$resource_name", $data);
    }


    private function assertLocationIsProviden()
    {
        $response = $this->client->getResponse();
        $decoded = json_decode($response->getContent());
        $this->assertTrue(isset($decoded->resource_name));
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function assertAlreadyExistingErrorIsReturned()
    {
        $response = $this->client->getResponse();
        $this->assertEquals(409, $response->getStatusCode());
    }
}