<?php

namespace Wekreit\OrangeSms;

use Wekreit\OrangeSms\Http\Post;

class OrangeSms
{
    // BASE URL Constantes
    const BASE_URL = 'https://api.orange.com';
    const TOKEN = '/oauth/v3/token';
    const MESSANGING = '/smsmessaging/v1';
    const CONTRACT_MANAGEMENT = '/sms/admin/v1';
    const TPS_LIMIT = 5;
    
    protected $clientId = '';
    protected $applicationId = '';
    protected $clientSecret = '';
    protected $token = '';
    protected $countrySenderNumber = '2250000';

    function __construct($config = [])
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

        if (array_key_exists('token', $config)) {
            $this->token = $config['token'];
        }
        
        if (array_key_exists('countrySenderNumber', $config)) {
            $this->countrySenderNumber = $config['countrySenderNumber'];
        }
    }

    private function sendSmsRequest(string $recipientPhoneNumber, string $SmsTextMessage)
    {
        define('URL_API',
               self::BASE_URL .
               self::MESSANGING .
               "/outbound/tel%3A%2B" .
               $this->getSenderAddress() .
               "/request"
        );

        $header =  array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $this->token
        );

        $data = [
            "outboundSMSMessageRequest" => [
                "address" => "tel+" . $recipientPhoneNumber,
                "senderAddress" => "tel+" . $this->getSenderAddress(),
                "outboundSMSTextMessage" => [
                    "message" => $SmsTextMessage,
                ],
            ]
        ];

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
}

