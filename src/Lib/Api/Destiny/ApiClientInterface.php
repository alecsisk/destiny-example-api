<?php


namespace App\Lib\Api\Destiny;


use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;

interface ApiClientInterface
{
    /**
     * @param string $membershipType
     * @param string $membershipId
     * @param array $components
     * @return ApiResponseInterface
     * @throws ApiException
     */
    public function getProfile(string $membershipType, string $membershipId, array $components): ApiResponseInterface;

    /**
     * @param string $nickname
     * @return ApiResponseInterface
     * @throws ApiException
     */
    public function searchPlayer(string $nickname): ApiResponseInterface;
}