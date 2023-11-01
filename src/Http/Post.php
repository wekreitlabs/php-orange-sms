<?php

namespace Wekreit\Http;

class Post {
    private $url;
    private $options;

    public function __construct($url, array $options = [])
    {
        $this->url = $url;
        $this->options = $options;
    }

    public function __invoke($post)
    {
        $ch = curl_init($this->url);

        foreach($this->options as $key => $value)
        {
            \curl_setopt($ch, $key, $value);
        }
        
        \curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $reponse = \curl_exec($ch);
        $error   = \curl_error($ch);
        $errno   = \curl_errno($ch);

        if (\is_resource($ch))
        {
            \curl_close($ch);
        }

        if(0 !== $errno)
        {
            throw new \RuntimeException($error, $errno);
        }

        return $reponse;
    }

}
