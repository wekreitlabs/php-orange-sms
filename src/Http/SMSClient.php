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
    
    protected static $clientId     = '';
    protected static $clientSecret = '';

    protected $applicationId       = '';
    protected static $token               = '';
    protected $countrySenderNumber = 'tel:+2250000';

    public static function initClient(
        $clientId = null,
        $clientSecret = null,
        $token = null)
    {
        if (isset($clientId) && isset($clientSecret)) {
            return self::pullNewToken($clientId, $clientSecret);
        }

        if (isset($token)){
            //...
        }
    }

    protected static function pullNewToken(string $clientId, string $clientSecret)
    {
        $client = new Client([ 'base_uri' => self::BASE_URL ]);
        $response = $client->request('POST', self::TOKEN, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials'
            ]
        ]);

        return json_decode($response->getBody())->access_token;
    }

    protected static function setToken($token)
    {
        self::$token = $token;
    }

    public function getToken()
    {
       return self::$token; 
    }

}
