<?php

namespace Wekreit\OrangeSms;

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
        $ch = curl_init();

        define('URL_API',
               self::BASE_URL .
               self::MESSANGING .
               "/outbound/tel%3A%2B" .
               $this->getSenderAddress() .
               "/request");

        $header =  array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $this->token
        );

        $data = array("outboundSMSMessageRequest" => array(
            "address" => "tel+" . $recipientPhoneNumber,
            "senderAddress" => "tel+" . $this->getSenderAddress(),
            "outboundSMSTextMessage" => array(
                "message" => $SmsTextMessage,
            ),
        ));
                
        curl_setopt($ch, CURLOPT_URL, URL_API);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $server_output = array($output,$http_code);
        
        curl_close($ch);

        return $server_output;
    }

    public function sendSms(string $recipient_phone_number, $text_message)
    {
        $response = $this->sendSmsRequest($recipient_phone_number, $text_message);

        if ($response['http_code'] === 201)
        {
            return $response;
        }
        
    }

    public function getSenderAddress(): string
    {
        return $this->countrySenderNumber;
    }
}

