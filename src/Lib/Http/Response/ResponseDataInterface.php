<?php


namespace App\Lib\Http\Response;


interface ResponseDataInterface
{
    /**
     * body
     * @return string|null
     */
    public function getBody(): ?string;

    public function getHttpCode(): int;

    public function getHeaders(): array;
}