<?php

namespace App\Service\Destiny;

use App\Lib\Api\Destiny\ApiClient;
use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\Basic\CheckHttpStatus;
use App\Lib\Api\Destiny\Method\MethodInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use App\Lib\Http\RequestManagerInterface;

class ApiClientDecorator extends ApiClient
{
    /**
     * @var int
     */
    private $apiMaxErrors;

    private $errorsCount;

    public function __construct(RequestManagerInterface $manager, string $apiKey, int $apiMaxErrors)
    {
        parent::__construct($manager, $apiKey);
        $this->apiMaxErrors = $apiMaxErrors;
    }

    /**
     * @param MethodInterface $request
     * @return ApiResponseInterface
     * @throws \Exception
     */
    protected function execute(MethodInterface $request): ApiResponseInterface
    {
        $this->errorsCount = 0;
        return $this->tryExecute($request);
    }


    /**
     * @param MethodInterface $request
     * @return ApiResponseInterface
     * @throws \Exception
     */
    private function tryExecute(MethodInterface $request): ApiResponseInterface
    {
        try {
            return parent::execute($request);
        } catch (HandlerException $exception) {
            $this->errorsCount++;
            if ($this->errorsCount === $this->apiMaxErrors || !$this->handleException($exception)) {
                throw $exception;
            }
        }
    }

    private function handleException(HandlerException $exception): bool
    {
        switch ($exception->getUniqueName()) {

            case CheckHttpStatus::class . CheckHttpStatus::CODE_GATEWAY_TIMEOUT:
                $this->timeout();
                return true;
        }
        return false;
    }

    private function timeout(): void
    {
        sleep(5);
    }
}