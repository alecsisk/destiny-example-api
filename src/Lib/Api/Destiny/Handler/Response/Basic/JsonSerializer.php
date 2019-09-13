<?php

namespace App\Lib\Api\Destiny\Handler\Response\Basic;

use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\SerializeException;
use App\Lib\Api\Destiny\Handler\Response\BasicResponseHandler;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;
use ErrorException;

class JsonSerializer extends BasicResponseHandler
{
    /**
     * @param ResponseDataInterface $response
     * @param ApiResponseEditInterface $data
     *
     * @return ApiResponseInterface
     * @throws ApiException
     *
     */
    public function handle(ResponseDataInterface $response, ApiResponseEditInterface $data): ApiResponseInterface
    {
        try {
            $json = json_decode($data->getData(), true);
        } catch (ErrorException $exception) {
            throw new SerializeException('Cant unserialize collection data: ' . $exception->getMessage());
        }

        $data->setData($json);

        return $this->next($response, $data);
    }
}