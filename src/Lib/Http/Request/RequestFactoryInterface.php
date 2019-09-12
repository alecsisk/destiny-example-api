<?php

namespace App\Lib\Http\Request;

use App\Lib\Http\Request\Data\RequestDataInterface;

interface RequestFactoryInterface
{
    public function create(RequestDataInterface $request): RequestInterface;
}