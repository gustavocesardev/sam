<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Formulario;
use App\Domain\Repository\FormularioRepositoryInterface;
use Illuminate\Support\Collection;

class FormularioRepository implements FormularioRepositoryInterface
{
    public function find(int $id): Formulario
    {
        return Formulario::findOrFail($id);
    }
    public function findAtivoByUsuario(int $idUsuario): Collection
    {
        return Formulario::where('id_usuario', $idUsuario)
                ->where('situacao', 'A')
                ->get();
    }

    public function store(array $data): Formulario
    {
        return Formulario::create($data)->refresh();
    }

    public function update(int $id, array $data): Formulario
    {
        $formulario = $this->find($id);
        $formulario->fill($data)->save();
        $formulario->refresh();

        return $formulario;
    }

    public function delete(int $id): bool
    {
        $formulario = $this->find($id);
        return $formulario->excluir();
    }
}