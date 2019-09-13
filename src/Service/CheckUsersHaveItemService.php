<?php

namespace App\Service;

use App\Event\MessageEvent;
use App\Event\ProgressEvent;
use App\Event\UserHaveItemEvent;
use App\Lib\Api\Destiny\Exception\ApiException;
use App\Lib\Api\Destiny\Exception\CollectiblesPrivacyException;
use App\Repository\Destiny\UserRepositoryInterface;
use App\Service\Destiny\Exception\UserHaveItemException;
use App\Service\Destiny\UserHaveItemHandler;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CheckUsersHaveItemService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserHaveItemHandler
     */
    private $userHaveItemHandler;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserHaveItemHandler $userHaveItemHandler,
        EventDispatcherInterface $dispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->userHaveItemHandler = $userHaveItemHandler;
        $this->dispatcher = $dispatcher;
    }


    /**
     * @throws Exception
     */
    public function checkUsers(string $itemId): void
    {
        $size = $this->userRepository->size();
        for ($i = 0; $i < $size; $i++) {
            $destinyUser = @$this->userRepository->first();
            try {
                $handlerResult = $this->userHaveItemHandler->handle($destinyUser->getName(), $itemId);
            } catch (UserHaveItemException $exception) {
                $this->dispatcher->dispatch(new MessageEvent($exception->getMessage()));
                continue;
            } finally {
                $this->dispatcher->dispatch(new ProgressEvent($i + 1, $size));
                $this->userRepository->removeByName($destinyUser->getName());
            }
            $this->dispatcher->dispatch(new UserHaveItemEvent($destinyUser->getName(), $handlerResult));
        }
    }
}