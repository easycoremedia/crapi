<?php

namespace Example\Response;

use Easycore\Crapi\InvalidResponseHandler;

class CorruptedResponseHandler extends ValidResponseHandler implements InvalidResponseHandler
{
    /**
     * @return bool
     */
    function isMatched()
    {
        // 0.5 %
        return rand(0, 1000) <= 5;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "corrupted";
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
        $response = substr($response, 0, floor(rand(600, 700) / 1000 * strlen($response)));
        echo $response;
        return;
    }


}