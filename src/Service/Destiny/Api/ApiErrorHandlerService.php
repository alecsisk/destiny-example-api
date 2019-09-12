<?php

namespace App\Service\Destiny\Api;

use App\Lib\Api\Destiny\Handler\HandlerException;
use App\Lib\Api\Destiny\Handler\Response\Basic\CheckHttpStatus;

class ApiErrorHandlerService
{
    public function handle(HandlerException $exception): bool
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