<?php

namespace Html2Pdf\Test\Functional;

class CreatePdfTest extends Html2PdfTestCase
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
        $this->requestResourceCreation('output');

        $this->assertResourceExists('output');
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


    private function requestResourceCreation($resource_name)
    {
        $data = array('content' => '<html><head></head><body>Some html</body></html>');
        $this->client->request('PUT', "/$resource_name", $data);
    }

}