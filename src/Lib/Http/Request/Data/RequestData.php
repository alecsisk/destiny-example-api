<?php


namespace App\Lib\Http\Request\Data;

class RequestData implements RequestDataInterface
{
    protected $url;
    protected $requestType;
    protected $headers;
    protected $urlParameters;
    protected $postParameters;

    public function __construct($requestType, $url) {
        $this->requestType = $requestType;
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRequestType(): string
    {
        return $this->requestType;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function getUrlParameters(): ?array
    {
        return $this->urlParameters;
    }

    public function getPostParameters(): ?array
    {
        return $this->postParameters;
    }
}