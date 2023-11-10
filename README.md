# php-orange-sms
Send SMS with Orange SMS API in PHP

# How to Use
First create a new client instance by provide `clientId` and `clientSecret` from your Orange API SMS subscrition and from that you can use it:
```php
<?php
use Wekreit\Http\SMSClient;
use Wekreit\SMS;

$client = SMSClient::initInstance("clientId", "clientSecret");

$sms = new SMS($client);
$sms->from("2250000")
    ->to("2250709000000")
    ->message("Test orange api sms")
    ->send();

``` 