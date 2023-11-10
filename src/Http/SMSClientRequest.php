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
    abstract public function getToken();
    abstract protected function pullNewToken(string $clientId,string $clientSecret);
    abstract protected function setToken($token);
    abstract protected function sendSMS($outboundSMSMessageRequest);
    // TODO : Implement this methods
    // abstract public function getApplicationId();
    // abstract public function setApplicationId($applicationId);

}
