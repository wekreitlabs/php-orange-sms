<?php

namespace Wekreit\Http;

/**
 * Class SMSClientRequest
 * @method mixed getToken()
 * @method mixed sendSMS(array $outboundSMSMessageRequest = [])
 * @method mixed pullNewToken(string $clientId, string $clientSecret)
 * @method mixed initInstance($clientOrToken = null, $clientSecret = null)
 * @method mixed send()
 * @method mixed getToken()
 */
abstract class SMSClientRequest
{
    abstract public static function initInstance($token);
    abstract public function send();
    abstract public function getToken();
    abstract public function pullNewToken($clientId, $clientSecret);
    abstract public function setToken($token);
    abstract public function sendSMS(array $outboundSMSMessageRequest = []);
    abstract public function from(string $senderAddress);
    abstract public function to(string $recipientPhoneNumber);
    abstract public function message(string $textMessage);

    // TODO : Implement this methods
    // abstract public function getApplicationId();
    // abstract public function setApplicationId($applicationId);

}
