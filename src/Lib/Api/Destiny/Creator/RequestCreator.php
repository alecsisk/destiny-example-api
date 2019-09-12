<?php

namespace App\Lib\Api\Destiny\Creator;

use App\Lib\Api\Destiny\Handler\Request\HandlerInterface;
use App\Lib\Api\Destiny\Handler\Request\RequestHandlerManagerInterface;
use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;
use App\Lib\Http\Request\Data\RequestEditData;

/**
 * Class RequestCreator
 * @package App\Lib\Api\Destiny\Creator
 *
 * create request class
 */
class RequestCreator implements RequestCreatorInterface
{
    /**
     * @var RequestHandlerManagerInterface
     */
    private $handlerManager;

    public function __construct(RequestHandlerManagerInterface $handlerManager)
    {
        $this->handlerManager = $handlerManager;
    }

    /**
     * @param HandlerInterface[] $handlers
     */
    public function create(MethodDataInterface $method, array $handlers): RequestDataInterface
    {
        $request = new RequestEditData($method->getRequestType(), $method->getBaseUrl());

        return $this->handlerManager->run($handlers, $method, $request);
    }
}