<?php
namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Repository\GrupoEstudo\GrupoEstudoRepositoryInterface;
use Illuminate\Support\Collection;

class GrupoEstudoRepository implements GrupoEstudoRepositoryInterface
{
    public function find(int $id): GrupoEstudo | null
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

    public function searchByUsuarioCriador(int $idUsuario, int $limite = 15, $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;
        
        return GrupoEstudo::where('id_usuario', $idUsuario)
                ->skip($offset)
                ->limit($limite)
                ->get();
    }

    public function searchMostPopularNaoIngressadosByCurso(int $idUsuario, int $idCurso, int $limite = 15, $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return GrupoEstudo::withCount([
                    'membros' => function ($query) {
                        $query->where('situacao', 'A');
                    }
                ])
                ->whereDoesntHave('membros', function($query) use ($idUsuario) {
                    $query->where('id_usuario', $idUsuario)
                        ->where('situacao', 'A');
                })
                ->whereHas('user', function ($query) use ($idCurso) {
                    $query->where('id_curso', $idCurso);
                })
                ->orderByDesc('membros_count')
                ->skip($offset)
                ->limit($limite)
                ->get();
    }
}