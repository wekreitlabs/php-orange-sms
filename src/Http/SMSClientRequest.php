<?php

namespace Wekreit\Http;

abstract class SMSClientRequest
{
    abstract public static function initInstance($clientId, $clientSecret);
}
