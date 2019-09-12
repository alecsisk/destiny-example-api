<?php

namespace App\Lib\Api\Destiny\Creator;

use App\Lib\Api\Destiny\Handler\Response\HandlerInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

interface ResponseCreatorInterface
{
    public function create(ResponseDataInterface $response): ApiResponseInterface;

    /**
     * @param HandlerInterface[] $handlers
     */
    public function setHandlers(array $handlers):void;
}