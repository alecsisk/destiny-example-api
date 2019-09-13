<?php


namespace App\Lib\Api\Destiny\Handler\Response\Basic;


use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\HttpStatusException;
use App\Lib\Api\Destiny\Handler\Response\BasicResponseHandler;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class CheckHttpStatus extends BasicResponseHandler
{
    public const VALID_HTTP_STATUS = [200, 201];

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
        $responseHttpStatus = $response->getHttpCode();

        if (!in_array($responseHttpStatus, self::VALID_HTTP_STATUS)) {
            throw new HttpStatusException($response->getBody(), $responseHttpStatus);
        }

        return $this->next($response, $data);
    }
}