<?php

namespace Example\Response;

use Easycore\Crapi\JsonEditor;
use Easycore\Crapi\ResponseHandler;
use Example\Helper\TemperatureReplacer;

class ValidResponseHandler implements ResponseHandler
{
    /**
     * @return string
     */
    function getName()
    {
        return "valid";
    }

    /**
     * @return void
     */
    function doRespond()
    {
        $response = $this->createResponseBody();
        if ($response === null) {
            // TODO handle error
            return null;
        }

        header('Content-Type: application/json');
        echo $response;
        return;
    }

    /**
     * Parses body from source JSON.
     *
     * @return string
     */
    protected function createResponseBody()
    {
        $filename = __DIR__ . '/../../static/source.json';
        $loadedFileContents = file_get_contents($filename);
        if ($loadedFileContents === false) {
            return null;
        }

        $jsonEditor = new JsonEditor($loadedFileContents);
        $jsonEditor->registerReplacer(new TemperatureReplacer());
        $response = $jsonEditor->commit();
        return $response;
    }
}