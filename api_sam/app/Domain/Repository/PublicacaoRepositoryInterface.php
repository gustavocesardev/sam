<?php

namespace App\Domain\Repository;

use App\Domain\Model\Abstract\AbstractPublicacao;

interface PublicacaoRepositoryInterface
{
    public function find(int $id): AbstractPublicacao;

    public function store(array $data): AbstractPublicacao;

    public function update(int $id, array $data): AbstractPublicacao;

    public function delete(int $id): bool;

    public function save(AbstractPublicacao $publicacao): void;
}
