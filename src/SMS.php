<?php
namespace Wekreit;

use Wekreit\Exception\SMSClientException;
use Wekreit\Http\SMSClient;

/**
 * Class SMS
 * @method SMS message(string $textMessage)
 * @method SMS to(string $recipientPhoneNumber)
 * @method SMS from(string $senderAddress)
 * @method mixed send()
 * @method mixed getToken()
 * @package Wekreit
 */
class SMS 
{
    protected $message;
    protected string $recipientPhoneNumber = 'tel:+';
    protected string $senderAddress = 'tel:+';
    protected string $token;
    protected $client;
    
    /**
     * SMS constructor.
     * @param SMSClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param string $textMessage
     * @return $this
     * @throws SMSClientException
     */
    public function message(string $textMessage)
    {
        $this->message = ['message' => $textMessage];
        return $this;
    }

    /**
     * @param string $recipientPhoneNumber
     * @return $this
     * @throws SMSClientException
     */
    public function to(string $recipientPhoneNumber)
    {
        $this->recipientPhoneNumber .= $recipientPhoneNumber;
        return $this;
    }

    /**
     * @param string $senderAddress
     * @return $this
     * @throws SMSClientException
     */
    public function from(string $senderAddress)
    {
        $this->senderAddress .= $senderAddress;
        return $this;
    }

    /**
     * @return array
    */
    private function outboundSMSMessageRequest()
    {
        return ["outboundSMSMessageRequest" => [
            "address" => $this->recipientPhoneNumber,
            "senderAddress" => $this->senderAddress,
            "outboundSMSTextMessage" => $this->message
        ]];
    }

    /**
     * @return mixed
     * @throws SMSClientException
     */
    public function send()
    {
        //... User SMSClient to send the SMS
        // return var_dump(json_encode($this->outboundSMSMessageRequest()));
        return $this->client->sendSms(json_encode($this->outboundSMSMessageRequest()));
    }

    /**
     * @return mixed
     * @throws SMSClientException
     */
    public function getToken()
    {
        return $this->token = $this->client->getToken();
    }
}
