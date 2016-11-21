<?php

namespace Easycore\Crapi;

interface Replacer
{
    /**
     * Returns string of requested replacement matched by $key.
     *
     * @param $key string
     * @return string
     */
    function replaceKey($key);
}