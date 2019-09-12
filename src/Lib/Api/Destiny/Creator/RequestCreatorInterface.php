<?php

namespace App\Lib\Api\Destiny\Creator;

use App\Lib\Api\Destiny\Handler\Request\HandlerInterface;
use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;

interface RequestCreatorInterface
{
    /**
     * @param HandlerInterface[] $handlers
     */
    public function create(MethodDataInterface $method, array $handlers): RequestDataInterface;
}