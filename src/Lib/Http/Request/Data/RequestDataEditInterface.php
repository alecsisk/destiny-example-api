<?php

namespace App\Lib\Http\Request\Data;

interface RequestDataEditInterface extends RequestDataInterface
{
    public function addHeader(string $value): void;

    /**
     * @param string $name
     * @param string|array $value
     */
    public function addUrlParameter(string $name, $value): void;

    public function removeUrlParameter(string $name): void;

    /**
     * @param string $name
     * @param string|array $value
     */
    public function addPostParameter(string $name, $value): void;

    public function removePostParameter(string $name): void;

    public function setUrl(string $url): void;

    public function setRequestType(string $requestType): void;
}