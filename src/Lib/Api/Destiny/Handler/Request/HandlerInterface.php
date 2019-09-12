<?php

namespace App\Lib\Api\Destiny\Handler\Request;

use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataEditInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;

interface HandlerInterface
{
    /**
     * @param MethodDataInterface $method
     * @param RequestDataEditInterface $request
     *
     * @throws HandlerException
     *
     * @return RequestDataInterface
     */
    public function handle(MethodDataInterface $method, RequestDataEditInterface $request):RequestDataInterface;
    public function setNext(HandlerInterface $handler);
}