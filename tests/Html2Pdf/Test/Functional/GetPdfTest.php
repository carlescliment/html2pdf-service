<?php

namespace Html2Pdf\Test\Functional;

class GetPdfTest extends Html2PdfTestCase
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
        $this->createResource('output');

        $this->client->request('GET', '/output');

        $this->assertResponseIsSuccessful();
    }

}