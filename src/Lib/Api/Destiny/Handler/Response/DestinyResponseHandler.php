<?php


namespace App\Lib\Api\Destiny\Handler\Response;


use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\BasicResponseHandler;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class DestinyResponseHandler extends BasicResponseHandler
{
    public const FIELD_ERROR = 'Error';
    public const FIELD_RESPONSE = 'Response';

    public const CODE_UNKNOWN = 1;

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
        $responseData = $data->getData();

        if (array_key_exists(self::FIELD_ERROR, $responseData)) {
            throw new HandlerException($data[self::FIELD_ERROR], self::class, self::CODE_UNKNOWN);
        }

        if (!array_key_exists(self::FIELD_RESPONSE, $responseData)) {
            throw new HandlerException('Cant find response field in json', self::class, self::CODE_UNKNOWN);
        }

        $data->setData($responseData[self::FIELD_RESPONSE]);

        return $this->next($response, $data);
    }
}