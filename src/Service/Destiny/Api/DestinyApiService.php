<?php

namespace App\Service\Destiny\Api;

use App\Lib\Api\Destiny\ApiClient;
use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Method\MethodInterface;
use App\Lib\Api\Destiny\Response\ApiResponseInterface;
use \Exception;

class DestinyApiService
{
    private $client;
    private $errorHandler;

    private $errorCount;
    /**
     * @var int
     */
    private $maxErrorCount;

    public function __construct(ApiClient $client, ApiErrorHandlerService $errorHandler, $maxErrorCount)
    {
        $this->client = $client;
        $this->errorHandler = $errorHandler;
        $this->maxErrorCount = $maxErrorCount;
    }

    /**
     * @param MethodInterface $request
     *
     * @return ApiResponseInterface
     *
     * @throws Exception|HandlerException
     */
    public function execute(MethodInterface $request): ApiResponseInterface
    {
        $this->errorCount = 0;
        return $this->run($request);
    }


    /**
     * @param MethodInterface $request
     * @return ApiResponseInterface
     * @throws Exception
     */
    private function run(MethodInterface $request): ApiResponseInterface
    {
        try {

            return $this->client->execute($request);

        } catch (HandlerException $e) {

            if ($this->handleError($e)) {
                return $this->run($request);
            }

            throw $e;
        }
    }


    /**
     * @param HandlerException $e
     * @return bool
     */
    private function handleError(HandlerException $e): bool
    {
        $this->errorCount++;

        if ($this->errorCount > $this->maxErrorCount) {
            return false;
        }

        return $this->errorHandler->handle($e);
    }
}