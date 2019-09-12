<?php

namespace App\Lib\Http;

use App\Lib\Http\Request\Data\RequestDataInterface;
use App\Lib\Http\Response\ResponseDataInterface;

interface RequestManagerInterface
{
    public function execute(RequestDataInterface $request): ResponseDataInterface;
}