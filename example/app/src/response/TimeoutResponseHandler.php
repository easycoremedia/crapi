<?php

namespace Example\Response;

use Easycore\Crapi\InvalidResponseHandler;

class TimeoutResponseHandler implements InvalidResponseHandler
{
    /**
     * @return bool
     */
    function isMatched()
    {
        // 1 %
        return rand(0, 1000) <= 10;
    }

    /**
     * @return string
     */
    function getName()
    {
        return "timeout";
    }

    /**
     * @return void
     */
    function doRespond()
    {
        sleep(20);
        header("HTTP/1.0 408 Request Timeout");
        return;
    }
}