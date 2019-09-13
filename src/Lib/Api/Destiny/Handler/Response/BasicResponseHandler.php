<?php

namespace App\Lib\Api\Destiny\Handler\Response;

use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

abstract class BasicResponseHandler implements HandlerInterface
{
    /** @var HandlerInterface */
    private $nextHandler;


    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     *
     * @return ApiResponseInterface
     * @throws ApiException
     *
     */
    public abstract function handle(
        ResponseDataInterface $response,
        ApiResponseEditInterface $data
    ): ApiResponseInterface;


    public final function setNext(HandlerInterface $handler): void
    {
        $this->nextHandler = $handler;
    }

    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     * @return ApiResponseInterface
     * @throws ApiException
     */
    public final function next(ResponseDataInterface $response, ApiResponseEditInterface $data): ApiResponseInterface
    {
        if (isset($this->nextHandler)) {
            return $this->nextHandler->handle($response, $data);
        }

        return $data;
    }
}