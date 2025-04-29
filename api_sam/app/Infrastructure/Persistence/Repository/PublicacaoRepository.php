<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Publicacao;
use App\Domain\Model\User;
use App\Domain\Repository\PublicacaoRepositoryInterface;

class PublicacaoRepository implements PublicacaoRepositoryInterface
{
    public function find(int $id): Publicacao
    {
        return Publicacao::findOrFail($id);
    }

    public function store(array $data): Publicacao
    {
        return Publicacao::create($data);
    }

    public function update(int $id, array $data): Publicacao
    {
        $publicacao = $this->find($id);
        $publicacao->update($data);
        $publicacao->refresh();

        return $publicacao;
    }

    public function delete(int $id): bool
    {
        $publicacao = $this->find($id);
        return $publicacao->excluir();
    }

    public function save(Publicacao $publicacao): void
    {
        $publicacao->save();
    }
}
