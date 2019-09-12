<?php

namespace App\Lib\Api\Destiny\Handler\Request;

use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataEditInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;

/**
 * Class RequestHandlerManager
 * @package App\Lib\Api\Destiny\Handler\Request
 *
 * request middleware chain
 */
class RequestHandlerManager implements RequestHandlerManagerInterface
{
    /**
     * @var array
     */
    private $handlers;

    public function __construct($handlers = [])
    {
        $this->handlers = $handlers;
    }

    /**
     * @param HandlerInterface[] $handlers
     * @throws \App\Lib\Api\Destiny\Handler\HandlerException
     */
    public function run(
        array $handlers,
        MethodDataInterface $method,
        RequestDataEditInterface $request
    ): RequestDataInterface {

        // add initialize handlers
        $handlers = array_merge($this->handlers, $handlers);

        if (count($handlers) === 0) {
            return $request;
        }

        /** @var HandlerInterface $previousHandler * */
        $previousHandler = null;

        // for setNext method
        $handlers = array_reverse($handlers);

        array_walk($handlers, function ($handler, $key) use (&$previousHandler) {

            /** @var HandlerInterface $handler * */
            if (isset($previousHandler)) {
                $handler->setNext($previousHandler);
            }

            $previousHandler = $handler;
        });

        return $previousHandler->handle($method, $request);
    }
}