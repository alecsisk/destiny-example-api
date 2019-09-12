<?php


namespace App\Lib\File;


interface FileHandlerInterface
{
    /**
     * open file with path
     *
     * @param string $path
     */
    public function open(string $path): void;

    /**
     * read line with number
     *
     * @param int $numberOfLine
     * @return string|null
     */
    public function readLine(int $numberOfLine): ?string;

    /**
     * append to end file
     *
     * @param string $data
     * @param bool $newLine
     */
    public function append(string $data, bool $newLine = true): void;

    /**
     * remove line with number
     *
     * @param int $numberOfLine
     */
    public function removeLine(int $numberOfLine): void;

    /**
     * get count lines in file
     *
     * @return int
     */
    public function getLineCount(): int;

    /**
     * get line number with $lineData (first match)
     *
     * @param string $lineData
     * @return int
     */
    public function searchLineByData(string $lineData): int;

    /**
     * clear all file
     */
    public function clear(): void;

    /**
     * close file
     */
    public function close(): void;
}