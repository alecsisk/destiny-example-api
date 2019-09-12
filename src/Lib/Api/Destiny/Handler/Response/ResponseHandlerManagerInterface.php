<?php

namespace App\Lib\Api\Destiny\Handler\Response;

use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

interface ResponseHandlerManagerInterface
{
    /**
     * @param HandlerInterface[] $handlers
     */
    public function run(
        array $handlers,
        ResponseDataInterface $response,
        ApiResponseEditInterface $formObject
    ): ApiResponseInterface;
}