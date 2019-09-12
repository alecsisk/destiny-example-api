<?php

namespace App\Lib\Api\Destiny\Method;

use App\Lib\Http\Request\Type\RequestTypeInterface;

interface MethodDataInterface
{
    /**
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * @return string
     * @see RequestTypeInterface
     */
    public function getRequestType(): string;


    /**
     * @return array key=>value
     */
    public function getParameters(): array;
}