<?php

namespace App\Service\Result;

class ResultFormatter
{

    public function format(string $username, bool $hasItem): string
    {
        $hasItemStr = $hasItem ? 'has' : 'no';
        return $username . ' - ' . $hasItemStr;
    }
}