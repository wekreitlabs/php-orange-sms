<?php

namespace Wekreit\Http;

abstract class SMSClientRequest
{
    abstract public static function initClient($clientId, $clientSecret);
    abstract public function setToken();
    abstract public function getToken();
}
