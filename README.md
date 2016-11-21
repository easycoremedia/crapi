#crapi
Simple library to create unreliable API responder.

What it is used for?
----------------------
Creating simple API that always replies with simple response (eg. JSON string), is pretty easy.
But what if you want to simulate real-world issues that usually comes with connecting to API?
That's when this library comes in hand. 

How it works?
-------------
```php
$responseGenerator = new UnreliableResponseGenerator(...);

$responseGenerator->registerInvalidResponse(...);
$responseGenerator->registerInvalidResponse(...);

$responseGenerator->getResponse()->doRespond();
```
You simply create object of `UnreliableResponseGenerator` and supply object implementing `ResponseHandler`.
This object will be used to generate default valid value.

Then you register various invalid responses, each of them supplies its own match conditions.

When `getResponse()` method is called upon response generator, response is selected and
with `doRespond()` call, application responds, and script should finish.

Example
-------
You can view our detailed example in `example` folder. This example responds on success with
data from thermal and humidity sensors.