<?php

namespace Example\Response;

use Easycore\Crapi\InvalidResponseHandler;

class ErrorResponseHandler implements InvalidResponseHandler
{
    const ERRORS = [
        404 => "Page not found.",
        401 => "Unauthorized",
        403 => "Forbidden",
        410 => "Gone",
        500 => "Internal server error",
        502 => "Bad Gateway",
        504 => "Gateway Time-out",

    ];

    /**
     * @return bool
     */
    function isMatched()
    {
        // 5 %
        return rand(0, 1000) <= 50;
    }

    /**
     * @return string
     */
    function getName()
    {
        return "error";
    }

    /**
     * @return void
     */
    function doRespond()
    {
        $errorCode = array_rand(self::ERRORS);
        http_response_code($errorCode);
        echo self::ERRORS[$errorCode];
        return;
    }
}