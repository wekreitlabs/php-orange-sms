<?php

namespace Wekreit\Http;

abstract class SMSClientRequest
{
    abstract static function initClient($clientId, $clientSecret);
    abstract public function getToken();
}
