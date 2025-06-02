<?php
namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Repository\GrupoEstudo\GrupoEstudoRepositoryInterface;

class GrupoEstudoRepository implements GrupoEstudoRepositoryInterface
{
    public function find(int $id): GrupoEstudo
    {
        return GrupoEstudo::findOrFail($id);
    }

    public function store(array $data): GrupoEstudo
    {
        return GrupoEstudo::create($data);
    }

    public function update(int $id, array $data): GrupoEstudo
    {
        $grupoEstudo = $this->find($id);
        $grupoEstudo->fill($data)->save();
        $grupoEstudo->refresh();

        return $grupoEstudo;
    }

    public function delete(int $id): bool
    {
        $grupoEstudo = $this->find($id);
        return $grupoEstudo->excluir();
    }
}