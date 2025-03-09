<?php

namespace App\Domain\Repository;

use App\Domain\Model\Instituicao;
use Illuminate\Support\Collection;

interface InstituicaoRepositoryInterface
{
    public function findAll(): Collection;

    public function find(int $id): Instituicao;
    public function findByDominio(string $dominio): ?Instituicao;

    public function store(array $data): Instituicao;

    public function update(int $id, array $data): Instituicao;

    public function delete(int $id): bool;
}
