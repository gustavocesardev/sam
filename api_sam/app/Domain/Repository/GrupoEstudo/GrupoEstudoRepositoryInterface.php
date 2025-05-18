<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;

interface GrupoEstudoRepositoryInterface
{
    public function find(int $id): GrupoEstudo;
    public function store(array $data): GrupoEstudo;
    public function update(int $id, array $data): GrupoEstudo;
    public function delete(int $id): bool;
}
