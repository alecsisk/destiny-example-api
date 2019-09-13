<?php

namespace App\Command;

use App\Event\ActionEvent;
use App\Event\MessageEvent;
use App\Service\CheckUsersHaveItemService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CheckUsersHaveItemCommand
 * @package App\Command\Destiny
 */
class CheckUsersHaveItemCommand extends Command
{
    /**
     * @var CheckUsersHaveItemService
     */
    private $checkUsersHaveItemService;
    /**
     * @var string
     */
    private $itemId;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(CheckUsersHaveItemService $checkUsersHaveItemService, string $itemId,
        EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->checkUsersHaveItemService = $checkUsersHaveItemService;
        $this->itemId = $itemId;

        parent::__construct('destiny:search:item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->dispatcher->dispatch(new ActionEvent('Check destiny users for item id: ' . $this->itemId));
            $this->checkUsersHaveItemService->checkUsers($this->itemId);
            $this->dispatcher->dispatch(new ActionEvent('No users for handle'));
        } catch (Exception $e) {
            $this->dispatcher->dispatch(new MessageEvent('Error: ' . $e->getMessage()));
        }
    }


    protected function configure()
    {
        $this->setDescription('Search destiny item_id in users collections');
    }
}