<?php

namespace Easycore\Crapi;

class JsonEditor
{
    /** @var string */
    private $originalJson;

    /** @var Replacer */
    private $replacer;

    /**
     * JsonEditor constructor.
     * @param $originalJson string
     */
    public function __construct($originalJson)
    {
        $this->originalJson = $originalJson;
    }

    /**
     * @param Replacer $replacer
     */
    public function registerReplacer(Replacer $replacer)
    {
        $this->replacer = $replacer;
    }

    /**
     * @return string
     */
    public function commit()
    {
        $json = $this->originalJson;
        if ($this->replacer != null) {
            $json = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) {
                list(, $key) = $matches;
                $obj = $this->replacer->replaceKey($key);
                return json_encode($obj);
            }, $json);
        }

        // pretty format
        $json = json_decode($json);

        return json_encode($json, JSON_PRETTY_PRINT);
    }
}