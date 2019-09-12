<?php

namespace App\Lib\Http\Request\Data;

interface RequestDataInterface
{
    public function getUrl(): string;

    public function getHeaders(): ?array;

    public function getRequestType(): string;

    public function getPostParameters(): ?array;

    public function getUrlParameters(): ?array;
}