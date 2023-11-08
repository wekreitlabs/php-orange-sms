<?php
namespace Wekreit;

use Wekreit\Exception\SMSClientException;
use Wekreit\Http\SMSClient;

class SMS 
{
    protected array $message = [];
    protected string $recipientPhoneNumber = 'tel:+';
    protected string $senderAddress = 'tel:+';
    protected string $token;
    
    public function __construct($client)
    {
        $this->token = $client;
    }

    public function message(string $textMessage)
    {
        $this->message = ['message' => $textMessage];
        return $this;
    }

    public function to(string $recipientPhoneNumber)
    {
        $this->recipientPhoneNumber .= $recipientPhoneNumber;
        return $this;
    }

    public function from(string $senderAddress)
    {
        $this->senderAddress .= $senderAddress;
        return $this;
    }

    private function outboundSMSMessageRequest()
    {
        return ["outboundSMSMessageRequest" => [
            "address" => $this->recipientPhoneNumber,
            "senderMessage" => $this->senderAddress,
            "outboundSMSTextMessage" => $this->message
        ]];
    }

    public function send()
    {
        //... User SMSClient to send the SMS
        return $this->outboundSMSMessageRequest();
    }

    public function getToken()
    {
        return $this->token;
    }
}
