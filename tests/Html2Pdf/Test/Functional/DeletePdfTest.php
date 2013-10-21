<?php

namespace Html2Pdf\Test\Functional;

class DeletePdfTest extends Html2PdfTestCase
{

    /**
     * @test
     */
    public function itDeletesExistingFiles()
    {
        $this->createResource('output');

        $this->requestFileDeletion('output');

        $this->assertFileDoesNotExist('output.pdf');
    }


    private function requestFileDeletion($file_name)
    {
        $this->client->request('DELETE', "/$file_name");
    }


    private function assertFileDoesNotExist($file_name)
    {
        $full_name = '/tmp/' . $file_name;
        $this->assertFalse(file_exists($full_name));
    }

}