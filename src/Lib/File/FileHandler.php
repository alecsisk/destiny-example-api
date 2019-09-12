<?php


namespace App\Lib\File;

use \SplFileObject;
use SplTempFileObject;
use Exception;

class FileHandler implements FileHandlerInterface
{
    /** @var SplFileObject */
    protected $file;


    /**
     * open file with path
     *
     * @param string $path
     * @throws \Exception
     */
    public function open(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception('File not found: ' . $path);
        }

        $this->file = new SplFileObject($path, 'r+');
        $this->file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::READ_AHEAD);
        $this->file->flock(LOCK_EX);
    }


    /**
     * read line with number
     *
     * @param int $numberOfLine
     * @return string|null
     */
    public function readLine(int $numberOfLine): ?string
    {
        // seek and rewind bad work with my php version
        foreach ($this->file as $line => $data) {

            if ($numberOfLine === $line) {
                return $data;
            }
        }

        return null;
    }

    /**
     * append to end file in new line
     *
     * @param string $data
     * @param bool $newLine
     */
    public function append(string $data, bool $newLine = true): void
    {
        while ($this->file->eof() === false) {
            $this->file->next();
        }

        $data = $this->getData($data, $newLine);
        $this->file->fwrite($data);
    }


    /**
     * remove line with number
     *
     * @param int $removedLine
     */
    public function removeLine(int $removedLine): void
    {
        // create new temp file
        $temp = new SplTempFileObject(0);
        $this->file->flock(LOCK_EX);

        $this->writeTempFileExceptLine($temp, $removedLine);
        $this->saveFromTempFile($temp);

        $this->file->flock(LOCK_UN);
        $temp = null;
    }


    /**
     * close file
     */
    public function close(): void
    {
        $this->file->flock(LOCK_UN);
        $this->file = null;
    }


    /**
     * get file data count lines
     *
     * @return int
     */
    public function getLineCount(): int
    {
        $blankFirstLine = false;
        $counter = 0;

        foreach ($this->file as $line => $data)
        {
            $blankFirstLine = $line === 0 && empty($data);
            $counter++;
        }

        // we have empty file
        if ($blankFirstLine && $counter === 1) {
            return 0;
        }

        return $counter;
    }


    /**
     * get line number with $lineData (first match)
     *
     * @param string $lineData
     * @return int
     */
    public function searchLineByData(string $lineData): int
    {
        foreach ($this->file as $line => $data) {
            if ($data === $lineData) {
                return $line;
            }
        }

        return -1;
    }


    /**
     * clear all file
     */
    public function clear(): void
    {
        $this->file->ftruncate(0);
        $this->file->rewind();
    }


    /**
     * write tempFile from file except one line
     *
     * @param SplTempFileObject $tempFile
     * @param int $removeLine
     */
    private function writeTempFileExceptLine(SplTempFileObject $tempFile, int $removeLine): void
    {
        $firstLineWrite = false;
        foreach ($this->file as $line => $data) {

            // except remove line
            if ($line !== $removeLine) {
                $data = $this->getData($data, $firstLineWrite);
                $tempFile->fwrite($data);
                $firstLineWrite = true;
            }
        }
    }

    /**
     * save data from temp file
     *
     * @param SplTempFileObject $tempFile
     */
    private function saveFromTempFile(SplTempFileObject $tempFile): void
    {
        $this->clear();

        foreach ($tempFile as $line) {
            $this->file->fwrite($line);
        }
    }

    /**
     * if $newLine then add PHP_EOL in begin
     *
     * @param string $data
     * @param bool $newLine
     *
     * @return string
     */
    private function getData(string $data, bool $newLine): string
    {
        return $newLine ? PHP_EOL . $data : $data;
    }
}