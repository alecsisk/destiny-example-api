<?php

namespace App\Lib\Api\Destiny\Handler\Response;

use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

/**
 * Class ResponseHandlerManager
 * @package App\Lib\Api\Destiny\Handler\Response
 *
 * response handlers and serializers
 */
class ResponseHandlerManager implements ResponseHandlerManagerInterface
{
    /**
     * @var array
     */
    private $initializeHandlers;

    public function __construct($initializeHandlers = [])
    {
        $this->initializeHandlers = $initializeHandlers;
    }

    /**
     * @param HandlerInterface[] $handlers
     * @throws \App\Lib\Api\Destiny\Handler\HandlerException
     */
    public function run(
        array $handlers,
        ResponseDataInterface $response,
        ApiResponseEditInterface $formObject
    ): ApiResponseInterface {

        $handlers = array_merge($this->initializeHandlers, $handlers);

        // no handlers - no handled data
        if (count($handlers) === 0) {
            return $formObject;
        }

        /** @var HandlerInterface $previousHandler */
        $previousHandler = null;

        // for setNext method
        $handlers = array_reverse($handlers);

        array_walk($handlers, function ($handler, $key) use (&$previousHandler) {

            /** @var HandlerInterface $handler */
            if (isset($previousHandler)) {
                $handler->setNext($previousHandler);
            }

            $previousHandler = $handler;
        });

        return $previousHandler->handle($response, $formObject);
    }
}