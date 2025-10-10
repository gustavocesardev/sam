<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Formulario;
use App\Domain\Repository\FormularioRepositoryInterface;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class FormularioRepository implements FormularioRepositoryInterface
{
    public function find(int $id): Formulario
    {
        return Formulario::findOrFail($id);
    }
    public function findAtivoByUsuario(int $idUsuario, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return Formulario::with(['usuario', 'usuario.curso'])
                ->where('id_usuario', $idUsuario)
                ->where('situacao', 'A')
                ->skip($offset)
                ->limit($limite)
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

    public function filtrarPorCampos(int $idInstituicao, array $filters, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return Formulario::with(['usuario', 'usuario.curso'])
            ->whereHas('usuario.curso', function ($query) use ($idInstituicao) {
                $query->where('id_instituicao', $idInstituicao);
            })
            ->when(!empty($filters['titulo']) || !empty($filters['descricao']), function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    if (!empty($filters['titulo'])) {
                        $q->orWhere('titulo', 'ILIKE', '%' . $filters['titulo'] . '%');
                    }
                    if (!empty($filters['titulo'])) {
                        $q->orWhere('descricao', 'ILIKE', '%' . $filters['titulo'] . '%');
                    }
                });
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
            ->whereDate('data_limite', '>=', Carbon::today())
            ->skip($offset)
            ->limit($limite)
            ->get();
    }
}