<?php

namespace Easycore\Crapi;

class UnreliableResponseGenerator
{
    /** @var ResponseHandler */
    private $defaultResponse;

    /** @var array array(InvalidResponseHandler) */
    private $invalidResponses = [];

    /** @var int */
    private $seed;

    /**
     * UnreliableResponseGenerator constructor.
     * @param ResponseHandler $defaultResponse
     */
    public function __construct(ResponseHandler $defaultResponse)
    {
        $this->defaultResponse = $defaultResponse;
    }

    /**
     * Registers invalid response.
     *
     * @param InvalidResponseHandler $invalidResponse
     */
    public function registerInvalidResponse(InvalidResponseHandler $invalidResponse)
    {
        $this->invalidResponses[] = $invalidResponse;
    }

    /**
     * Set seed that will be used for generating random values.
     *
     * @param $seed
     */
    public function setSeed($seed)
    {
        $this->seed = $seed;
    }

    /**
     * Returns matched response.
     *
     * @return ResponseHandler
     */
    public function getResponse()
    {
        srand($this->seed);

        /** @var InvalidResponseHandler $invalidResponse */
        foreach ($this->invalidResponses as $invalidResponse) {
            if ($invalidResponse->isMatched()) {
                return $invalidResponse;
            }
        }

        return $this->defaultResponse;
    }

    /**
     * Returns specified response.
     *
     * @param $responseName string
     * @return ResponseHandler|null
     */
    public function forceResponse($responseName)
    {
        $responses = array_merge($this->invalidResponses, [$this->defaultResponse]);

        /** @var ResponseHandler $response */
        foreach ($responses as $response) {
            if ($response->getName() == $responseName) {
                return $response;
            }
        }

        return null;
    }
}