<?php


namespace App\Lib\Api\Destiny;


use App\Lib\Api\Destiny\Response\ApiResponseInterface;

interface ApiClientInterface
{
    public function getProfile(string $membershipType, string $membershipId, array $components): ApiResponseInterface;

    public function searchPlayer(string $nickname): ApiResponseInterface;
}