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
    abstract public static function initInstance(string | null $clientOrToken, string | null $clientSecret) : self;
    abstract public function getToken(): string;
    abstract protected function pullNewToken(string $clientId,string $clientSecret): string;
    abstract protected function setToken(string $token): void;
    /**
     * @param array $outboundSMSMessageRequest
     * @return array<string>
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    abstract protected function sendSMS(array $outboundSMSMessageRequest): string;
    // TODO : Implement this methods
    // abstract public function getApplicationId();
    // abstract public function setApplicationId($applicationId);

}
