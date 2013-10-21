<?php

namespace Html2Pdf\Test\Functional;

use Silex\WebTestCase;

use carlescliment\Html2Pdf\Application\Application;

class DeletePdfTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient();
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
    public function itDeletesExistingFiles()
    {
        $this->createFile('output.pdf');

        $this->requestFileDeletion('output');

        $this->assertFileDoesNotExist('output.pdf');
    }


    private function requestFileDeletion($file_name)
    {
        $this->client->request('DELETE', "/$file_name");
    }


    private function createFile($file_name)
    {
        $file = fopen('/tmp/' . $file_name, 'w');
        fclose($file);
    }

    private function assertFileDoesNotExist($file_name)
    {
        $full_name = '/tmp/' . $file_name;
        $this->assertFalse(file_exists($full_name));
    }

}