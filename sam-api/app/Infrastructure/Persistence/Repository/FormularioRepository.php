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

    public function filtrarPorCampos(array $filters, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return Formulario::query()
            ->when(!empty($filters['titulo']), function ($query) use ($filters) {
                $query->where('titulo', 'ILIKE', '%' . $filters['titulo'] . '%');
            })
            ->when(!empty($filters['descricao']), function ($query) use ($filters) {
                $query->where('descricao', 'ILIKE', '%' . $filters['descricao'] . '%');
            })
            ->when(!empty($filters['tipo']), function ($query) use ($filters) {
                $query->where('tipo', $filters['tipo']);
            })
            ->when(!empty($filters['id_curso']), function ($query) use ($filters) {
                $query->whereHas('usuario.curso', function ($query) use ($filters) {
                    $query->where('id', $filters['id_curso']);
                });
            })
            ->when(!empty($filters['data_limite']), function ($query) use ($filters) {
                $query->whereDate('data_limite', '<=', $filters['data_limite']);
            })
            ->skip($offset)
            ->limit($limite)
            ->get();
    }
}