<?php

namespace App\Lib\Http\Request;

interface RequestInterface
{
    public function getUrl(): string;

    public function getHeaders(): ?array;

    public function getRequestType(): string;

    public function getPostFields(): ?string;
}