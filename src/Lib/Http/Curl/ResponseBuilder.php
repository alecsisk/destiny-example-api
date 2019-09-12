<?php


namespace App\Lib\Http\Curl;


use App\Lib\Http\Response\ResponseDataInterface;

class ResponseBuilder implements ResponseDataInterface
{
    private $headers;
    private $body;
    private $httpCode;

    public function __construct($data, $ch)
    {
        $headersSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $this->body = substr($data, $headersSize);

        $headers = substr($data, 0, $headersSize);
        $this->headers = explode("\n", $headers);
    }

    /**
     * body
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}