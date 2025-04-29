<?php

namespace App\Domain\Repository;

use App\Domain\Model\Publicacao;

interface PublicacaoRepositoryInterface
{
    public function find(int $id): Publicacao;

    public function store(array $data): Publicacao;

    public function update(int $id, array $data): Publicacao;

    public function delete(int $id): bool;
    public function save(Publicacao $user): void;
}
