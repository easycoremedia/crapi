<?php

require __DIR__ . '/app/autoload.php';

use Easycore\Crapi\UnreliableResponseGenerator;
use Example\Response\CorruptedResponseHandler;
use Example\Response\ErrorResponseHandler;
use Example\Response\TimeoutResponseHandler;
use Example\Response\ValidDelayedResponseHandler;
use Example\Response\ValidResponseHandler;

$responseGenerator = new UnreliableResponseGenerator(new ValidResponseHandler());
$responseGenerator->setSeed(floor(time() / 30));
$responseGenerator->registerInvalidResponse(new CorruptedResponseHandler());
$responseGenerator->registerInvalidResponse(new ErrorResponseHandler());
$responseGenerator->registerInvalidResponse(new TimeoutResponseHandler());
$responseGenerator->registerInvalidResponse(new ValidDelayedResponseHandler());

if (isset($_GET['resp']) && $_GET['resp']) {
    $response = $responseGenerator->forceResponse($_GET['resp']);
} else {
    $response = $responseGenerator->getResponse();
}

if ($response) {
    $response->doRespond();
} else {
    http_response_code(404);
}

exit;