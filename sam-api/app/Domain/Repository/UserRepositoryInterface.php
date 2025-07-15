<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function find(int $id): User;
    public function findWithCountArtigoPublicacao(int $id): User;
    public function findWithCurso(int $id): User;
    public function findAll(): Collection;
    public function findByEmail(string $email): ?User;
    public function store(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
    public function save(User $user): void;
}
