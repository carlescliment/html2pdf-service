<?php

namespace Html2Pdf\Test\Functional;

class GeneratePdfTest extends Html2PdfTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->deleteResourceIfExists('output');
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

        $this->assertResponseIsConflict();
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
        $this->assertResponseIsSuccessful();
    }

}