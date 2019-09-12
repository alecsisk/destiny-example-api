<?php

namespace App\Lib\Http\Request\Type;

use App\Lib\Http\Request\BasicRequest;

class GetRequest extends BasicRequest
{
    public final function getPostFields(): ?string
    {
        return null;
    }
}