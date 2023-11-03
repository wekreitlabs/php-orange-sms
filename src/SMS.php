<?php
namespace Wekreit;

use Wekreit\Exception\SMSClientException;
use Wekreit\Http\SMSClient;

class SMS 
{
    protected $message = '';
    protected $recipientPhoneNumber = 'tel:+';
    protected $senderAddress = 'tel:+';
    protected $clientInstance;
    
    public function __construct($client)
    {
        $this->clientInstance = $client;
    }

    public function message(string $textMessage)
    {
        $this->message = $textMessage;
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

    public function send()
    {
        //...
    }
}
