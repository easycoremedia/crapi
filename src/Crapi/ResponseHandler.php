<?php

namespace Easycore\Crapi;

interface ResponseHandler
{
    /**
     * Returns name of response handler, which can be used to force response.
     *
     * @return string
     */
    function getName();

    /**
     * Sends respond to client. After that, script should quit.
     *
     * @return void
     */
    function doRespond();
}