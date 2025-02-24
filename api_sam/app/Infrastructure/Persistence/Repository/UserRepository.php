<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $id): User
    {
        return User::findOrFail( $id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function store(array $data): User
    {
        return User::create($data);
    }
}