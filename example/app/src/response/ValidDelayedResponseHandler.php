<?php

namespace Example\Response;

use Easycore\Crapi\InvalidResponseHandler;

class ValidDelayedResponseHandler extends ValidResponseHandler implements InvalidResponseHandler
{

    /**
     * Returns whether handler matches condition and should be run.
     *
     * @return bool
     */
    function isMatched()
    {
        return rand(0, 100) <= 10;
    }

    /**
     * @return string
     */
    function getName()
    {
        return "valid_delayed";
    }

    /**
     * @return void
     */
    function doRespond()
    {
        sleep(10);

        parent::doRespond();
    }


}