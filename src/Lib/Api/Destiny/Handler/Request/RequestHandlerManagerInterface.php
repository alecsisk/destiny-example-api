<?php

namespace App\Lib\Api\Destiny\Handler\Request;

use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataEditInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;

interface RequestHandlerManagerInterface
{
    /**
     * @param HandlerInterface[] $handlers
     */
    public function run(
        array $handlers,
        MethodDataInterface $method,
        RequestDataEditInterface $request
    ): RequestDataInterface;
}