<?php

namespace App\Lib\Api\Destiny\Method;

use App\Lib\Api\Destiny\Handler\Request\HandlerInterface as RequestHandler;
use App\Lib\Api\Destiny\Handler\Response\CheckNoPlayersFound;
use App\Lib\Api\Destiny\Handler\Response\MultiplePlayerHandler;
use App\Lib\Api\Destiny\Handler\Response\NoPlayersFoundHandler;
use App\Lib\Http\Request\Type\RequestTypeInterface;

class SearchPlayer implements MethodInterface
{
    public const PARAM_DISPLAY_NAME = '$displayName';

    /**
     * @var string
     */
    private $displayName;
    /**
     * @var bool
     */
    private $useCaseCompare;


    /**
     * SearchPlayer constructor.
     * @param string $displayName
     * @param bool $useCaseCompare if true nick and Nick is one record
     */
    public function __construct(string $displayName, bool $useCaseCompare = true)
    {
        $this->displayName = $displayName;
        $this->useCaseCompare = $useCaseCompare;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return 'https://www.bungie.net/platform/Destiny2/SearchDestinyPlayer/All/$displayName/';
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
            // in api documentation remove all spaces in nickname
            self::PARAM_DISPLAY_NAME => str_replace(' ', '', $this->displayName),
        ];
    }

    public function getResponseHandlers(): array
    {
        return [
            new MultiplePlayerHandler($this->displayName, $this->useCaseCompare),
            new CheckNoPlayersFound(),
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