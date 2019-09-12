<?php

namespace App\Lib\Api\Destiny\Creator;

use App\Lib\Api\Destiny\Handler\Response\HandlerInterface;
use App\Lib\Api\Destiny\Handler\Response\ResponseHandlerManagerInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Api\Destiny\Response\ResponseData;
use App\Lib\Http\Response\ResponseDataInterface;

/**
 * Class ResponseCreator
 * @package App\Lib\Api\Destiny\Creator
 *
 * create response
 */
class ResponseCreator implements ResponseCreatorInterface
{
    /**
     * @var ResponseHandlerManagerInterface
     */
    private $handlerManager;

    private $handlers = [];

    public function __construct(ResponseHandlerManagerInterface $handlerManager)
    {
        $this->handlerManager = $handlerManager;
    }

    public function create(ResponseDataInterface $response): ApiResponseInterface
    {
        $apiResponse = new ResponseData($response->getBody());

        return $this->handlerManager->run($this->getHandlers(), $response, $apiResponse);
    }

    /**
     * @param HandlerInterface[] $handlers
     */
    public function setHandlers(array $handlers): void
    {
        $this->handlers = $handlers;
    }


    protected function getHandlers():array
    {
        return $this->handlers;
    }
}