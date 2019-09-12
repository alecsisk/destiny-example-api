<?php

namespace App\Lib\Api\Destiny\Handler\Response\Basic;

use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\BasicResponseHandler;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class JsonSerializer extends BasicResponseHandler
{
    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     *
     * @return ApiResponseInterface
     * @throws HandlerException
     *
     */
    public function handle(ResponseDataInterface $response, ApiResponseEditInterface $data): ApiResponseInterface
    {
        $json = json_decode($data->getData(), true);

        $data->setData($json);

        return $this->next($response, $data);
    }
}