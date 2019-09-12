<?php

namespace App\Command;

use App\Lib\Api\Destiny\Exception\CollectiblesPrivacyException;
use App\Repository\Destiny\UserRepositoryInterface;
use App\Service\Destiny\UserHaveItemHandler;
use App\Service\Result\ResultFormatter;
use App\Service\Result\ResultService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Exception;

/**
 * Class CheckUsersHaveItemCommand
 * @package App\Command\Destiny
 */
class CheckUsersHaveItemCommand extends Command
{
    /**
     * @var string
     */
    private $itemId;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var ResultService
     */
    private $resultService;

    /** @var ResultOutputHelper */
    private $output;

    /** @var UserHaveItemHandler */
    private $userHaveItemHandler;

    /** @var ResultFormatter */
    private $formatter;

    public function __construct(
        string $itemId,
        UserRepositoryInterface $userRepository,
        ResultService $resultService,
        UserHaveItemHandler $userHaveItemHandler
    ) {
        $this->itemId = $itemId;
        $this->userHaveItemHandler = $userHaveItemHandler;
        $this->userRepository = $userRepository;
        $this->resultService = $resultService;

        $this->formatter = new ResultFormatter();

        parent::__construct('destiny:search:item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = new ResultOutputHelper($output);
        try {
            $this->output->setAction('Check destiny users for item id: ' . $this->itemId);
            $this->output->setProgress($this->userRepository->size());
            $this->checkUsers();
            $this->output->setAction('No users for handle');
        } catch (Exception $e) {
            $this->output->sendMessage('Error: ' . $e->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    private function checkUsers(): void
    {
        while ($this->userRepository->size() > 0) {
            $destinyUser = @$this->userRepository->first();
            try {
                $handlerResult = $this->userHaveItemHandler->handle($destinyUser->getName(), $this->itemId);
            } catch (CollectiblesPrivacyException $exception) {
                $this->output->sendMessage($exception->getMessage());
                continue;
            } finally {
                $this->output->advanceProgress();
                $this->userRepository->removeByName($destinyUser->getName());
            }
            $result = $this->formatter->format($destinyUser->getName(), $handlerResult);
            $this->output->sendMessage($result);
            $this->resultService->add($result);
        }
    }


    protected function configure()
    {
        $this->setDescription('Search destiny item_id in users collections');
    }
}