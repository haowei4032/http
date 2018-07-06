<?php

namespace EastWood\Http;

class HttpRequest
{
    const VERSION = '1.0.0';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_COPY = 'COPY';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_LINK = 'LINK';
    const METHOD_UNLINK = 'UNLINK';
    const METHOD_PURGE = 'PURGE';
    const METHOD_LOCK = 'LOCK';
    const METHOD_UNLOCK = 'UNLOCK';
    const METHOD_PROPFIND = 'PROPFIND';
    const METHOD_VIEW = 'VIEW';

    private $ch = null;
    private $_url = null;

    private $method = self::METHOD_GET;
    private $url = null;
    private $cookies = [];
    private $query = [];
    private $fragment = [];

    /**
     * HttpRequest constructor.
     * @param string $url [optional]
     * @param string $method [optional]
     * @param array $options [optional]
     */
    public function __construct($url = null, $method = HttpRequest::METHOD_GET, array $options = [])
    {
        $this->ch = curl_init();
        if ($url) $this->setUrl($url);
        $this->setMethod($method);
    }

    /**
     * @return \EastWood\Http\HttpResponse
     */
    public function send()
    {
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla 5.0 (' . __CLASS__ . ' v' . self::VERSION . ')');
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
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
     * @param array $cookies
     * @return HttpRequest
     */
    public function setCookies(array $cookies)
    {
        foreach ($cookies as $name => $value) {
            unset($cookies[$name]);
            array_push($cookies, $name . '=' . $value);
        }
        $this->cookies = $cookies;
        curl_setopt($this->ch, CURLOPT_COOKIE, implode(';', $this->cookies));
        return $this;
    }

    /**
     * @param string $method
     * @return HttpRequest
     */
    public function setMethod($method)
    {
        switch ($method) {
            case self::METHOD_GET:
            case self::METHOD_POST:
            case self::METHOD_PUT:
            case self::METHOD_PATCH:
            case self::METHOD_DELETE:
            case self::METHOD_COPY:
            case self::METHOD_HEAD:
            case self::METHOD_OPTIONS:
            case self::METHOD_LINK:
            case self::METHOD_UNLINK:
            case self::METHOD_PURGE:
            case self::METHOD_LOCK:
            case self::METHOD_UNLOCK:
            case self::METHOD_PROPFIND:
            case self::METHOD_VIEW:
                break;
            default:
                $method = self::METHOD_GET;
        }
        $this->method = $method;
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
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($fields));
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
     * @param string $contentType
     * @return HttpRequest
     */
    public function setContentType($contentType)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type' => $contentType]);
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