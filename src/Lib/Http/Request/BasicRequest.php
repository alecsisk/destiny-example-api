<?php

namespace App\Lib\Http\Request;

use App\Lib\Http\Request\Data\RequestDataInterface;
use \InvalidArgumentException;

abstract class BasicRequest implements RequestInterface
{
    protected $data;

    public function __construct(RequestDataInterface $data)
    {
        $this->data = $data;
    }

    final public function getRequestType(): string
    {
        return $this->data->getRequestType();
    }

    final public function getHeaders(): ?array
    {
        return $this->data->getHeaders();
    }

    public function getUrl(): string
    {
        return $this->getFullUrl($this->data->getUrl(), $this->data->getUrlParameters());
    }

    private function getFullUrl($url, $parameters): string
    {
        if (!isset($parameters) || count($parameters) === 0) {
            return $url;
        }

        $url .= '?';

        foreach ($parameters as $name => $value) {

            $valueType = gettype($value);

            switch ($valueType) {

                case 'array':
                    $url .= $this->serializeArrayParameter($name, $value);
                    break;

                case 'string':
                    $url .= $this->serializeStringParameter($name, $value);
                    break;

                default:
                    throw new InvalidArgumentException('$parameters value must be string or array. Given: ' . $valueType);
            }

            if (next($parameters) === true) {
                $url .= '&';
            }
        }

        return $url;
    }

    private function serializeStringParameter(string $name, string $value): string
    {
        return urlencode($name) . '=' . urlencode($value);
    }

    private function serializeArrayParameter(string $name, array $value): string
    {
        $parameter = array_map(static function (string $value) use ($name) {
            return urlencode($name) . '[]=' . urlencode($value);
        }, $value);

        return implode('&', $parameter);
    }
}