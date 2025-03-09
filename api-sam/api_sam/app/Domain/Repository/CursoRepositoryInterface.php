<?php

namespace App\Domain\Repository;

use App\Domain\Model\Curso;
use Illuminate\Support\Collection;

interface CursoRepositoryInterface
{
    public function findAll(): Collection;

    public function find(int $id): Curso;

    public function store(array $data): Curso;

    public function update(int $id, array $data): Curso;

    public function delete(int $id): bool;
}