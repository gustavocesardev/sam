<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepositoryInterface
{
    public function find(int $id): User;

    public function findByEmail(string $email): ?User;

    public function store(array $data): User;
}