<?php


namespace App\Lib\Api\Destiny\Handler\Response;


use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\ApiResponseException;
use App\Lib\Api\Destiny\Exception\UnknownApiResponseException;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class DestinyResponseHandler extends BasicResponseHandler
{
    public const FIELD_ERROR = 'Error';
    public const FIELD_RESPONSE = 'Response';

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
        $responseData = $data->getData();

        if (array_key_exists(self::FIELD_ERROR, $responseData)) {
            throw new ApiResponseException($data[self::FIELD_ERROR]);
        }

        if (!array_key_exists(self::FIELD_RESPONSE, $responseData)) {
            throw new UnknownApiResponseException('Cant find response field in json: ' . json_encode($responseData));
        }

        $data->setData($responseData[self::FIELD_RESPONSE]);

        return $this->next($response, $data);
    }
}