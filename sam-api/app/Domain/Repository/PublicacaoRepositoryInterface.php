<?php

namespace App\Domain\Repository;

use App\Domain\Model\Abstract\PublicacaoAbstract;

interface PublicacaoRepositoryInterface
{
    public function find(int $id): PublicacaoAbstract;
    public function store(array $data): PublicacaoAbstract;
    public function update(int $id, array $data): PublicacaoAbstract;
    public function delete(int $id): bool;
    public function save(PublicacaoAbstract $publicacao): void;
}
