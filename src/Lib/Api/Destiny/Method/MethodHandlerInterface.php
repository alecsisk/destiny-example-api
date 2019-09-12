<?php

namespace App\Lib\Api\Destiny\Method;

use App\Lib\Api\Destiny\Handler\Request\HandlerInterface as RequestHandler;
use App\Lib\Api\Destiny\Handler\Response\HandlerInterface as ResponseHandler;

interface MethodHandlerInterface
{
    /**
     * @return RequestHandler[]
     */
    public function getRequestHandlers(): array;

    /**
     * @return ResponseHandler[]
     */
    public function getResponseHandlers(): array;
}