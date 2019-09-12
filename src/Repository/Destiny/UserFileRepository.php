<?php

namespace App\Repository\Destiny;

use App\Entity\Destiny\User;
use App\Lib\File\FileHandlerInterface;

class UserFileRepository implements UserRepositoryInterface
{
    private $fileHandler;
    private $fileName;

    public function __construct(string $fileName, FileHandlerInterface $fileHandler)
    {
        $this->fileName = $fileName;
        $this->fileHandler = $fileHandler;
    }

    public function first(): ?User
    {
        $this->fileHandler->open($this->fileName);

        if ($this->fileHandler->getLineCount() === 0) {
            return null;
        }

        $result = $this->fileHandler->readLine(0);

        $this->fileHandler->close();

        return new User($result);
    }

    public function removeByName(string $name): void
    {
        $this->fileHandler->open($this->fileName);

        $removeLine = $this->fileHandler->searchLineByData($name);
        $this->fileHandler->removeLine($removeLine);

        $this->fileHandler->close();
    }

    public function size(): int
    {
        $this->fileHandler->open($this->fileName);

        $count = $this->fileHandler->getLineCount();

        $this->fileHandler->close();

        return $count;
    }
}