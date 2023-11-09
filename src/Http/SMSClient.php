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

    private function __construct($clientOrClientId, $clientSecret)
    {
        if ($clientOrClientId !== null && $clientSecret === null) {
            $this->setToken($clientOrClientId);
        } elseif ($clientOrClientId !== null && $clientSecret !== null) {
            $this->pullNewToken($clientOrClientId, $clientSecret);
        } else {
            throw new \Exception("Not clientId or clientSecret given");
        }
    }

    public static function initInstance($clientOrToken = null, $clientSecret = null)
    {
        return new self($clientOrToken, $clientSecret);
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

    public function sendSMS(array $outboundSMSMessageRequest = [])
    {
        define('SMSMessageRequestEndPoint', "outbound/tel%3A%2B" . $this->countrySenderNumber . "/requests");

        $client = new Client([ 'base_uri' => self::BASE_URL ]);
        $header = [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json'
        ];
        $response = $client->request('POST', self::MESSANGING . '/' . SMSMessageRequestEndPoint, [
            "headers" => $header,
            'json'    => json_encode($outboundSMSMessageRequest)
        ]
       );

        return $response->getBody()->getContents();
    }

    public function getToken()
    {
        return $this->token;
    }
}
