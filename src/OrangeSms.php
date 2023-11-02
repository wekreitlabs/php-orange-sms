<?php
namespace Wekreit;

use Wekreit\Http\Post;

class OrangeSms
{
    const BASE_URL = 'https://api.orange.com';
    const TOKEN = '/oauth/v3/token';
    const MESSANGING = '/smsmessaging/v1';
    const CONTRACT_MANAGEMENT = '/sms/admin/v1';
    
    protected $clientId = '';
    protected $applicationId = '';
    protected $clientSecret = '';
    protected $token = '';
    protected $countrySenderNumber = 'tel:+2250000';

    function __construct(array $config = [])
    {
        if(array_key_exists('clientId', $config))
        {
            $this->clientId = $config['clientId'];
        }

        if (array_key_exists('applicationId', $config)) {
            $this->applicationId = $config['applicationId'];
        }

        if (array_key_exists('clientSecret', $config)) {
            $this->clientSecret = $config['clientSecret'];
        }

        if (array_key_exists('countrySenderNumber', $config)) {
            $this->countrySenderNumber = $config['countrySenderNumber'];
        }
    }

    private function sendSmsRequest(string $recipientPhoneNumber, string $smsTextMessage)
    {
        define('URL_API',
               self::BASE_URL .
               self::MESSANGING .
               "/outbound/" .
               urlencode($this->getSenderAddress()) .
               "/requests"
        );

        $header = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ];

        $data = json_encode([
            "outboundSMSMessageRequest" => [
                "address" => "tel:" . $recipientPhoneNumber,
                "senderAddress" => $this->getSenderAddress(),
                "outboundSMSTextMessage" => [
                    "message" => $smsTextMessage,
                ],
            ]
        ]);

        $curl = new Post(URL_API, [
            CURLOPT_HTTPHEADER => $header,
        ]);

        try {
            return $curl($data);
        } catch (\RuntimeException $ex)
        {
            die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
        }
    }

    public function sendSms(string $recipient_phone_number, $text_message)
    {
        $response = $this->sendSmsRequest($recipient_phone_number, $text_message);
        return $response;
    }

    public function getSenderAddress(): string
    {
        return $this->countrySenderNumber;
    }

    public function getToken() {
        $token = $this->genToken();
        $this->token = $token;
        return $this->token;
    }

    private function genToken() {
        define('TOKEN_URL', self::BASE_URL . self::TOKEN);
        $header = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $curl = new Post(TOKEN_URL, [
            CURLOPT_HTTPHEADER => $header
        ]);

        $data = http_build_query([
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);

        try {
            $response = (array)json_decode($curl($data));

            return $response['access_token'];
        } catch (\RuntimeException $ex){
            die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
        }
    }

}

