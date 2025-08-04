<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Model\GrupoEstudo\Publicacao;
use App\Domain\Repository\GrupoEstudo\PublicacaoRepositoryInterface;

use Illuminate\Support\Collection;

class PublicacaoRepository implements PublicacaoRepositoryInterface
{
    public function find(int $id): Publicacao
    {
        return Publicacao::withCount(['publicacoesVinculadas as qtde_comentarios'])->findOrFail($id);
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

    public function save(PublicacaoAbstract $publicacao): void
    {
        $publicacao->save();
    }

    public function searchKeywords(int $idGrupoEstudo, array $keywords, int $limite = 10): Collection
    {
        $query = Publicacao::with(['membro.user', 'membro.user.curso'])
        ->whereHas('keywords', function ($query) use ($keywords) {
            $query->whereIn('keyword', $keywords);
        })
        ->withCount(['publicacoesVinculadas as qtde_comentarios']);

        if (!is_null($idGrupoEstudo))
        {
            $query->whereHas('membro.grupoEstudo', function ($query) use ($idGrupoEstudo) {
                $query->where('id', $idGrupoEstudo);
            });
        }

        return $query->limit($limite)->get();
    }

    public function searchByIds(array $ids): Collection
    {
        return Publicacao::with(['membro.user', 'membro.user.curso'])
            ->whereIn('id', $ids)
            ->withCount(['publicacoesVinculadas as qtde_comentarios'])
            ->get();
    }

    public function searchWithReacaoAndVisualizacao(array $ids): Collection
    {
        return $this->searchByIds($ids);
    }

    public function searchMostPopularPublicacoes(int $idGrupoEstudo, array $excluirIds = [], int $limite = 10): Collection
    {
        $query = Publicacao::with(['membro.user', 'membro.user.curso'])
            ->withCount(['publicacoesVinculadas as qtde_comentarios'])
            ->when(!empty($excluirIds), function ($query) use ($excluirIds) {
                $query->whereNotIn('id', $excluirIds);
            });

        if (!is_null($idGrupoEstudo))
        {
            $query->whereHas('membro.grupoEstudo', function ($query) use ($idGrupoEstudo) {
                $query->where('id', $idGrupoEstudo);
            });
        }

        return $query->limit($limite)->get();
    }
}
