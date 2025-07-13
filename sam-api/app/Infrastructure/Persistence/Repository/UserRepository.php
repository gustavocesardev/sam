<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;

use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    public function findWithCurso(int $id): User
    {
        return User::with('curso')->findOrFail($id);
    }

    public function findAll(): Collection
    {
        return User::all();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function store(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);
        $user->refresh();

        return $user;
    }

    public function delete(int $id): bool
    {
        $user = $this->find($id);
        return $user->excluir();
    }

    public function save(User $user): void
    {
        $user->save();
    }
}
