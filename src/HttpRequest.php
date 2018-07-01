<?php

namespace EastWood\Http;

class HttpRequest
{
    const METHOD_GET = 0x1;
    const METHOD_POST = 0x2;
    const METHOD_PUT = 0x3;
    const METHOD_DELETE = 0x4;
    const METHOD_HEAD = 0x5;
    const METHOD_OPTIONS = 0x6;

    private $ch = null;
    private $_url = null;

    private $url = null;
    private $query = [];
    private $fragment = [];

    /**
     * HttpRequest constructor.
     * @param string $url [optional]
     * @param int $method [optional]
     * @param array $options [optional]
     */
    public function __construct($url = null, $method = HttpRequest::METHOD_GET, array $options = [])
    {
        $this->ch = curl_init();
        if ($url) $this->setUrl($url);
        $this->setMethod($method);
    }

    /**
     * @return HttpResponse
     */
    public function send()
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_URL, $this->url .
            (count($this->query) ? '?' . http_build_query($this->query) : '') .
            (count($this->fragment) ? '#' . http_build_query($this->fragment) : ''));

        return new HttpResponse($this->ch);
    }

    /**
     * @param array $headers
     * @return HttpRequest
     */
    public function setHeaders(array $headers)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        return $this;
    }

    /**
     * @param string $url
     * @return HttpRequest
     */
    public function setUrl($url)
    {
        $parse = parse_url($url);
        $this->_url = $url;
        $this->url = sprintf('%s://%s', $parse['scheme'],
            $parse['host'] .
            (isset($parse['port']) ? ':' . $parse['port'] : '') .
            (isset($parse['path']) ? $parse['path'] : ''));
        if (isset($parse['query'])) parse_str($parse['query'], $this->query);
        if (isset($parse['fragment'])) parse_str($parse['fragment'], $this->fragment);
        return $this;
    }

    /**
     * @param array $query
     * @return HttpRequest
     */
    public function setQueryData(array $query)
    {
        $this->query = array_merge($this->query, $query);
        return $this;
    }

    /**
     * @param int $method
     * @return HttpRequest
     */
    public function setMethod($method)
    {
        switch ($method) {
            case 0x1:
                $method = 'GET';
                break;
            case 0x2;
                $method = 'POST';
                break;
            case 0x3;
                $method = 'PUT';
                break;
            case 0x4;
                $method = 'DELETE';
                break;
            case 0x5;
                $method = 'HEAD';
                break;
            case 0x6;
                $method = 'OPTIONS';
                break;

        }

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        return $this;
    }

    /**
     * @param string $name
     * @param resource $resource ;
     * @return HttpRequest
     */
    public function setPostFile($name, $resource = null)
    {
        return $this;
    }

    /**
     * @param array $fields
     * @return HttpRequest
     */
    public function setPostFields(array $fields)
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields);
        return $this;
    }

    /**
     * @param string $body
     * @return HttpRequest
     */
    public function setBody($body)
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, (string)$body);
        return $this;
    }

    /**
     * @param string $content_type
     * @return HttpRequest
     */
    public function setContentType($content_type)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type' => $content_type]);
        return $this;
    }

    /**
     * @param int $timeout
     * @return HttpRequest
     */
    public function setConnectTimeout($timeout)
    {
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
        return $this;
    }

    /**
     * @param int $timeout
     * @return HttpRequest
     */
    public function setReadTimeout($timeout)
    {
        curl_setopt($this->ch, CURLOPT_TIMEOUT_MS, $timeout);
        return $this;
    }

}