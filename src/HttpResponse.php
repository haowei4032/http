<?php

namespace EastWood\Http;

class HttpResponse
{
    private $ch = null;
    private $raw = null;
    private $info = null;
    private $header = null;
    private $body = null;

    public function __construct($ch)
    {
        $this->ch = $ch;
        $this->raw = curl_exec($this->ch);
        $this->info = curl_getinfo($this->ch);
        $this->header = substr($this->raw, 0, $this->info['header_size']);
        $this->body = substr($this->raw, $this->info['header_size']);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->info['http_code'];
    }

    public function asFile($file)
    {
        return file_put_contents($file, $this->getBody());
    }

    public function __toString()
    {
        return $this->getBody();
    }
}