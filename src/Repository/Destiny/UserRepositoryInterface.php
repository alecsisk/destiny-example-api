<?php

namespace App\Repository\Destiny;

use App\Entity\Destiny\User;

interface UserRepositoryInterface
{
    public function first(): ?User;

    public function removeByName(string $name): void;

    public function size(): int;
}