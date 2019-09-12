<?php


namespace App\Service\Result;


use App\Lib\File\FileHandlerInterface;

class ResultService
{
    private $fileHandler;
    private $fileName;

    public function __construct(string $fileName, FileHandlerInterface $fileHandler)
    {
        $this->fileName = $fileName;
        $this->fileHandler = $fileHandler;
    }


    public function add(string $result): void
    {
        $this->fileHandler->open($this->fileName);

        $useNewLine = $this->fileHandler->getLineCount() > 0;

        $this->fileHandler->append($result, $useNewLine);

        $this->fileHandler->close();
    }
}