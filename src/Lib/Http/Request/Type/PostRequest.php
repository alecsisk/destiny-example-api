<?php

namespace App\Lib\Http\Request\Type;

use App\Lib\Http\Request\BasicRequest;

class PostRequest extends BasicRequest
{

    public final function getPostFields(): ?string
    {
        return http_build_query($this->data->getPostParameters());
    }
}