<?php

namespace EastWood\Http;

class HttpResponse
{
    private $ch;
    private $info = null;

    public function __construct($ch)
    {
        $this->ch = $ch;
        curl_exec($this->ch);
        $this->info = curl_getinfo($this->ch);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return curl_multi_getcontent($this->ch);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->info['http_code'];
    }

    public function __toString()
    {
        return $this->getBody();
    }
}