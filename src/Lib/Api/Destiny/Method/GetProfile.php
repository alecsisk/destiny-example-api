<?php

namespace App\Lib\Api\Destiny\Method;

use App\Lib\Api\Destiny\Handler\Request\HandlerInterface as RequestHandler;
use App\Lib\Api\Destiny\Handler\Response\CheckCollectiblesPrivacy;
use App\Lib\Http\Request\Type\RequestTypeInterface;

class GetProfile implements MethodInterface
{
    public const PARAM_MEMBERSHIP_TYPE = '$membershipType';
    public const PARAM_MEMBERSHIP_ID = '$membershipId';
    public const PARAM_COMPONENTS = 'components';
    /**
     * @var string
     */
    private $membershipType;
    /**
     * @var string
     */
    private $membershipId;
    /**
     * @var array
     */
    private $components;


    public function __construct(string $membershipType, string $membershipId, array $components)
    {
        $this->membershipType = $membershipType;
        $this->membershipId = $membershipId;
        $this->components = $components;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return 'https://www.bungie.net/platform/Destiny2/$membershipType/Profile/$membershipId/';
    }

    /**
     * @return string
     * @see RequestTypeInterface
     */
    public function getRequestType(): string
    {
        return RequestTypeInterface::METHOD_GET;
    }

    /**
     * @return array key=>value
     */
    public function getParameters(): array
    {
        return [
            self::PARAM_MEMBERSHIP_TYPE => $this->membershipType,
            self::PARAM_MEMBERSHIP_ID => $this->membershipId,
            self::PARAM_COMPONENTS => $this->components,
        ];
    }

    public function getResponseHandlers(): array
    {
        return [
            new CheckCollectiblesPrivacy($this->components),
        ];
    }

    /**
     * @return RequestHandler[]
     */
    public function getRequestHandlers(): array
    {
        return [];
    }
}