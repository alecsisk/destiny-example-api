<?php

namespace App\Lib\Api\Destiny\Handler\Request;

use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Method\MethodDataInterface;
use App\Lib\Http\Request\Data\RequestDataEditInterface;
use App\Lib\Http\Request\Data\RequestDataInterface;

class AuthorizationToken extends BasicRequestHandler
{
    public const CODE_EMPTY_PARAMETERS = 1;
    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Dont forget call '$this->next()'
     *
     * @param MethodDataInterface $method
     * @param RequestDataEditInterface $request
     *
     * @return RequestDataInterface
     * @throws HandlerException
     *
     */
    public function handle(MethodDataInterface $method, RequestDataEditInterface $request): RequestDataInterface
    {
        if (empty($this->apiKey)) {
            throw new HandlerException('api token is empty', self::class, self::CODE_EMPTY_PARAMETERS);
        }

        $request->addHeader('X-API-Key: ' . $this->apiKey);

        return $this->next($method, $request);
    }
}