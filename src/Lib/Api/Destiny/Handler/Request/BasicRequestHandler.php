<?php


namespace App\Lib\Api\Destiny\Handler\Request;


use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataEditInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;

abstract class BasicRequestHandler implements HandlerInterface
{
    /** @var HandlerInterface */
    private $nextHandler;

    public final function setNext(HandlerInterface $handler)
    {
        $this->nextHandler = $handler;
    }

    /**
     * @param MethodDataInterface $method
     * @param RequestDataEditInterface $request
     *
     * @return RequestDataInterface
     *
     * @throws \App\Lib\Api\Destiny\Handler\HandlerException
     */
    public final function next(MethodDataInterface $method, RequestDataEditInterface $request)
    {
        if (isset($this->nextHandler)) {
            return $this->nextHandler->handle($method, $request);
        }

        return $request;
    }

    /**
     * Dont forget call '$this->next()'
     *
     * @param MethodDataInterface $method
     * @param RequestDataEditInterface $request
     *
     * @return RequestDataInterface
     * @throws HandlerException
     *
     */
    public abstract function handle(
        MethodDataInterface $method,
        RequestDataEditInterface $request
    ): RequestDataInterface;
}