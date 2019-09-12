<?php

namespace App\Lib\Api\Destiny\Handler\Response;

use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

interface HandlerInterface
{
    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     *
     * @throws HandlerException
     *
     * @return ApiResponseInterface
     */
    public function handle(ResponseDataInterface $response, ApiResponseEditInterface $data): ApiResponseInterface;

    public function setNext(HandlerInterface $handler): void;
}