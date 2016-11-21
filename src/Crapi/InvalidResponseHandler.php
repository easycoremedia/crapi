<?php

namespace Easycore\Crapi;

interface InvalidResponseHandler extends ResponseHandler
{
    /**
     * Returns whether handler matches condition and should be run.
     *
     * @return bool
     */
    function isMatched();
}