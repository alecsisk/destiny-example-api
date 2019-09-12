<?php

namespace App\Lib\Http\Curl;

use App\Lib\Http\Request\Data\RequestDataInterface;
use App\Lib\Http\Request\RequestFactoryInterface;
use App\Lib\Http\Request\RequestInterface;
use App\Lib\Http\Request\Type\GetRequest;
use App\Lib\Http\Request\Type\PostRequest;
use App\Lib\Http\Request\Type\RequestTypeInterface;
use InvalidArgumentException;

class RequestFactory implements RequestFactoryInterface
{
    public function create(RequestDataInterface $request): RequestInterface
    {
        switch ($request->getRequestType()) {

            case RequestTypeInterface::METHOD_POST:
                return new PostRequest($request);

            case RequestTypeInterface::METHOD_GET:
                return new GetRequest($request);

            default:
                throw new InvalidArgumentException('$request->getRequestType need only contains RequestTypeInterface::CONSTANT');
        }
    }
}