<?php
namespace Wekreit\Http;

use Wekreit\Http\SMSClientRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SMSClient
 */
class SMSClient extends SMSClientRequest
{
    const BASE_URL            = 'https://api.orange.com';
    const TOKEN               = '/oauth/v3/token';
    const MESSANGING          = '/smsmessaging/v1';
    const CONTRACT_MANAGEMENT = '/sms/admin/v1';
    
    protected string $clientId;
    protected string $clientSecret;

    protected string $applicationId;
    protected string $token;
    protected string $countrySenderNumber = 'tel:+2250000';

    /**
     * SMSClient constructor.
     * @param string | null $clientOrClientId
     * @param string | null $clientSecret
     * @throws \Exception
     */
    private function __construct(string | null $clientOrClientId, string | null $clientSecret)
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
    public static function initInstance($clientOrToken = null, $clientSecret = null): self
    {
        return new self($clientOrToken, $clientSecret);
    }

    /**
     * TODO : Implement Exception Handling
     * @param string $clientId
     * @param string $clientSecret
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function pullNewToken(string $clientId, string $clientSecret): string
    {
        $client = new Client([ 'base_uri' => self::BASE_URL ]);
        try {
            $response = $client->request('POST', self::TOKEN, [
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $decodedResponse = json_decode($response->getBody()->getContents());
            if (!is_object($decodedResponse) || !property_exists($decodedResponse, 'access_token')) {
                throw new \Error("No access token found");
            }

            $this->setToken($decodedResponse->access_token);
            return $this->getToken();

        } catch (GuzzleException $e) {
            throw new \Error($e->getMessage());
        }
    }

    /**
     * @param $token
     * @return void
     */
    protected function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * TODO : Implement Exception Handling
     * @param array<string, mixed> $outboundSMSMessageRequest
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSMS(array $outboundSMSMessageRequest): string
    {
        define('SMSMessageRequestEndPoint', "outbound/" . urlencode($this->countrySenderNumber ) . "/requests");

        $client = new Client([ 'base_uri' => self::BASE_URL ]);
        $header = [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json'
        ];
        $response = $client->request('POST', self::MESSANGING . '/' . SMSMessageRequestEndPoint, [
            "headers" => $header,
            "body" => json_encode($outboundSMSMessageRequest)
        ]
       );

        return $response->getBody()->getContents();
    }

    /**
     * TODO : Implement Exception Handling
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
