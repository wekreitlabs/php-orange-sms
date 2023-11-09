<?php
namespace Wekreit\Http;

use Wekreit\Http\SMSClientRequest;
use GuzzleHttp\Client;

class SMSClient extends SMSClientRequest
{
    const BASE_URL            = 'https://api.orange.com';
    const TOKEN               = '/oauth/v3/token';
    const MESSANGING          = '/smsmessaging/v1';
    const CONTRACT_MANAGEMENT = '/sms/admin/v1';
    
    protected $clientId;
    protected $clientSecret;

    protected $applicationId;
    protected $token;
    protected $countrySenderNumber = 'tel:+2250000';

    private function __construct($token)
    {
        $this->setToken($token);
    }

    public static function initInstance($token)
    {

        return new self($token);
    }

    protected function pullNewToken(string $clientId, string $clientSecret)
    {
        $client = new Client([ 'base_uri' => self::BASE_URL ]);
        $response = $client->request('POST', self::TOKEN, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials'
            ]
        ]);

        return $this->setToken(json_decode($response->getBody())->access_token);
    }

    protected function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
