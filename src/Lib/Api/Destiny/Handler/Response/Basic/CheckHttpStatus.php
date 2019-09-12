<?php


namespace App\Lib\Api\Destiny\Handler\Response\Basic;


use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\BasicResponseHandler;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class CheckHttpStatus extends BasicResponseHandler
{
    public const VALID_HTTP_STATUS = [200, 201];

    public const CODE_UNKNOWN = 1;
    public const CODE_BAD_REQUEST = 2;
    public const CODE_NOT_AUTHORIZED = 3;
    public const CODE_GATEWAY_TIMEOUT = 4;

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
        $responseHttpStatus = $response->getHttpCode();

        if (!in_array($responseHttpStatus, self::VALID_HTTP_STATUS)) {

            $code = $this->handleInvalidCode($responseHttpStatus);

            $exceptionStr = 'Incorrect response http status: ' . $responseHttpStatus . ', message:' .
                $response->getBody();

            throw new HandlerException($exceptionStr, CheckHttpStatus::class, $code);
        }

        return $this->next($response, $data);
    }


    private function handleInvalidCode(int $status): int
    {
        switch ($status) {

            case 400:
                return self::CODE_BAD_REQUEST;

            case 401:
                return self::CODE_NOT_AUTHORIZED;

            case 504:
                return self::CODE_GATEWAY_TIMEOUT;

            default:
                return self::CODE_UNKNOWN;
        }
    }
}