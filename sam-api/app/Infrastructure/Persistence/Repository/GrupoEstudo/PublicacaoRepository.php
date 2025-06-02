<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Model\GrupoEstudo\Publicacao;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

use DB;

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

    public function save(PublicacaoAbstract $publicacao): void
    {
        $publicacao->save();
    }

    // Início dos métodos referentes as buscas para filtragem híbrida
    public function searchKeywords(array $keywords, int $limite = 10): Collection
    {
        return Publicacao::whereHas('keywords', function ($query) use ($keywords) {
            $query->whereIn('keyword', $keywords);
        })
        ->withCount(['reacoes as likes', 'visualizacoes as views'])
        ->limit($limite)
        ->get();
    }

    public function searchKeywordsByCurso(int $idCurso, array $keywords, int $limite = 10): Collection
    {
        $query = Publicacao::whereHas('keywords', function ($query) use ($keywords) {
            $query->whereIn('keyword', $keywords);
        })
        ->whereHas('membro.user.curso', function ($query) use ($idCurso) {
            $query->where('id', $idCurso);
        })
        ->withCount(['reacoes as likes', 'visualizacoes as views']);

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->limit($limite)->get();
    }

    public function searchByIds(array $ids): Collection
    {
        return Publicacao::whereIn('id', $ids)
            ->withCount(['reacoes as likes', 'visualizacoes as views'])
            ->get();
    }

    public function searchWithReacaoAndVisualizacao(array $ids): Collection
    {
        return $this->searchByIds($ids);
    }

    public function searchMostPopularPublicacoes(array $excluirIds = [], int $limite = 10): Collection
    {
        return Publicacao::withCount(['reacoes as likes', 'visualizacoes as views'])
            ->when(!empty($excluirIds), function ($query) use ($excluirIds) {
                $query->whereNotIn('id', $excluirIds);
            })
            ->limit($limite)
            ->get();
    }

    public function searchMostPopularPublicacoesByCurso(int $idCurso, array $excluirIds = [], int $limite = 10): Collection
    {
        return Publicacao::withCount(['reacoes as likes', 'visualizacoes as views'])
            ->when(!empty($excluirIds), function ($query) use ($excluirIds) {
                $query->whereNotIn('id', $excluirIds);
            })
            ->whereHas('membro.user.curso', function ($query) use ($idCurso) {
                $query->where('id', $idCurso);
            })
            ->limit($limite)
            ->get();
    }
}
