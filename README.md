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
$sms->from("2250000") // senderAddress
    ->to("2250709000000") // Recipient phone number
    ->message("Test orange api sms") // text message
    ->send();

```
Or if you already have token:
```php
$client = SMSClient::initInstance("token");

$sms = new SMS($client);
$sms->from("2250000") // senderAddress
    ->to("2250709000000") // Recipient phone number
    ->message("Test orange api sms") // text message
    ->send();
```

For get token made:
```php
$client = SMSClient::initInstance("clientId", "clientSecret");
$client->getToken(); // return token 
```