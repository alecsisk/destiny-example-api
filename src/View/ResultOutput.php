<?php


namespace App\View;


use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ResultOutput
{
    /** @var OutputInterface */
    private $messages;
    /** @var OutputInterface */
    private $action;
    /** @var ProgressBar */
    private $progressBar;

    public function __construct(OutputInterface $output)
    {
        $this->messages = $output->section();
        $separator = $output->section();
        $this->action = $output->section();
        $progress = $output->section();

        $separator->write('=========================================');

        $this->progressBar = new ProgressBar($progress);
    }

    public function setAction(string $action): void
    {
        $this->action->write($action);
    }

    public function setMaxProgress(int $maxValue): void
    {
        $this->progressBar->setMaxSteps($maxValue);
    }

    public function setCurrentProgress(int $currentValue, string $message = null): void
    {
        $this->progressBar->setProgress($currentValue);

        if (isset($message)) {
            $this->sendMessage($message);
        }
    }

    public function sendMessage(string $message): void
    {
        $this->messages->writeln($message);
    }
}