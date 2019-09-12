<?php


namespace App\Lib\Http\Request\Data;


class RequestEditData extends RequestData implements RequestDataEditInterface
{
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setRequestType(string $requestType): void
    {
        $this->requestType = $requestType;
    }

    public function addHeader(string $value): void
    {
        $this->headers[] = $value;
    }

    /**
     * @param string $name
     * @param string|array $value
     */
    public function addUrlParameter(string $name, $value): void
    {
        $this->urlParameters[$name] = $value;
    }

    /**
     * @param string $name
     * @param string|array $value
     */
    public function addPostParameter(string $name, $value): void
    {
        $this->postParameters[$name] = $value;
    }

    public function removeUrlParameter(string $name): void
    {
        unset($this->urlParameters[$name]);
    }

    public function removePostParameter(string $name): void
    {
        unset($this->postParameters[$name]);
    }
}