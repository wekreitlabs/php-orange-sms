<?php
namespace Wekreit\Http;

use Wekreit\Http\SMSClientRequest;
use GuzzleHttp\Client;

/**
 * Class SMSClient
 * @method mixed getToken()
 * @method mixed sendSMS(array $outboundSMSMessageRequest = [])
 * @method mixed pullNewToken(string $clientId, string $clientSecret)
 * @method mixed initInstance($clientOrToken = null, $clientSecret = null)
 * @method mixed send()
 * @method mixed getToken()
 * @package Wekreit\Http
 */
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

    /**
     * SMSClient constructor.
     * @param $clientOrClientId
     * @param $clientSecret
     * @throws \Exception
     */
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

    /**
     * @param null $clientOrToken
     * @param null $clientSecret
     * @return SMSClient
     * @throws \Exception
     */
    public static function initInstance($clientOrToken = null, $clientSecret = null)
    {
        return new self($clientOrToken, $clientSecret);
    }

    /**
     * TODO : Implement Exception Handling
     * @param string $clientId
     * @param string $clientSecret
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

    /**
     * @param $token
     */
    protected function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * TODO : Implement Exception Handling
     * @param array $outboundSMSMessageRequest
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSMS($outboundSMSMessageRequest)
    {
        define('SMSMessageRequestEndPoint', "outbound/" . urlencode($this->countrySenderNumber ) . "/requests");

        $client = new Client([ 'base_uri' => self::BASE_URL ]);
        $header = [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json'
        ];
        $response = $client->request('POST', self::MESSANGING . '/' . SMSMessageRequestEndPoint, [
            "headers" => $header,
            "body" => $outboundSMSMessageRequest
        ]
       );

        return $response->getBody()->getContents();
    }

    /**
     * TODO : Implement Exception Handling
     * @return token
     */
    public function getToken()
    {
        return $this->token;
    }
}
