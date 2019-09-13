<?php


namespace App\Lib\Api\Destiny\Handler\Response;


use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\NoPlayersFoundException;
use App\Lib\Api\Destiny\Response\ApiResponseEditInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\Response\ResponseDataInterface;

class CheckNoPlayersFound extends BasicResponseHandler
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
        $responseData = $data->getData();

        if (count($responseData) === 0) {
            throw new NoPlayersFoundException('Player not found');
        }

        return $this->next($response, $data);
    }
}