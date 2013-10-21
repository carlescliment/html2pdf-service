<?php

namespace Html2Pdf\Test\Functional;

class DeletePdfTest extends Html2PdfTestCase
{

    /**
     * @test
     */
    public function itDeletesExistingResources()
    {
        $this->createResource('output');

        $this->requestResourceDeletion('output');

        $this->assertResourceDoesNotExist('output');
    }


    /**
     * @test
     */
    public function itReturnsANotFoundWhenDeletingUnexistingResources()
    {
        $this->deleteResourceIfExists('output');

        $this->requestResourceDeletion('output');

        $this->assertResponseIsNotFound();
    }


    private function requestResourceDeletion($file_name)
    {
        $this->client->request('DELETE', "/$file_name");
    }

}