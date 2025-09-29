<?php

namespace App\Infrastructure\Persistence\Repository\Publicacao;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Model\Publicacao\Publicacao;
use App\Domain\Model\Publicacao\PublicacaoReacao;
use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;

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

    public function searchKeywords(int $idInstituicao, array $keywords, int $limite = 10): Collection
    {
        $query = Publicacao::with(['user', 'user.curso'])
        ->whereHas('keywords', function ($query) use ($keywords) {
            $query->whereIn('keyword', $keywords);
        })
        ->withCount(['publicacoesVinculadas as qtde_comentarios'])
        ->whereNull('id_publicacao_vinculada');

        if (!is_null($idInstituicao))
        {
            $query->whereHas('user.curso.instituicao', function ($query) use ($idInstituicao) {
            $query->where('id', $idInstituicao);
            });
        }

        return $query->limit($limite)->get();
    }

    public function searchKeywordsByCurso(int $idCurso, array $keywords, int $limite = 10): Collection
    {
        $query = Publicacao::with(['user', 'user.curso'])
        ->whereHas('keywords', function ($query) use ($keywords) {
            $query->whereIn('keyword', $keywords);
        })
        ->whereHas('user.curso', function ($query) use ($idCurso) {
            $query->where('id', $idCurso);
        })
        ->withCount(['publicacoesVinculadas as qtde_comentarios'])
        ->whereNull('id_publicacao_vinculada');

        return $query->limit($limite)->get();
    }

    public function searchByIds(array $ids): Collection
    {
        return Publicacao::with(['user', 'user.curso'])
            ->whereIn('id', $ids)
            ->withCount(['publicacoesVinculadas as qtde_comentarios'])
            ->whereNull('id_publicacao_vinculada')
            ->get();
    }

    public function searchWithReacaoAndVisualizacao(array $ids): Collection
    {
        return $this->searchByIds($ids);
    }

    public function searchMostPopularPublicacoes(int $idInstituicao, array $excluirIds = [], int $limite = 10): Collection
    {
        $query = Publicacao::with(['user', 'user.curso'])
                ->withCount(['publicacoesVinculadas as qtde_comentarios'])
                ->whereNull('id_publicacao_vinculada')
                ->when(!empty($excluirIds), function ($query) use ($excluirIds) {
                    $query->whereNotIn('id', $excluirIds);
                });

        if (!is_null($idInstituicao))
        {
            $query->whereHas('user.curso.instituicao', function ($query) use ($idInstituicao) {
            $query->where('id', $idInstituicao);
            });
        }

        return $query->limit($limite)->get();
    }

    public function searchMostPopularPublicacoesByCurso(int $idCurso, array $excluirIds = [], int $limite = 10): Collection
    {
        return Publicacao::with(['user', 'user.curso'])
            ->withCount(['publicacoesVinculadas as qtde_comentarios'])
            ->whereNull('id_publicacao_vinculada')
            ->when(!empty($excluirIds), function ($query) use ($excluirIds) {
                $query->whereNotIn('id', $excluirIds);
            })
            ->whereHas('user.curso', function ($query) use ($idCurso) {
                $query->where('id', $idCurso);
            })
            ->limit($limite)
            ->get();
    }

    public function searchByUsuario(int $idUsuario, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return Publicacao::with(['user', 'user.curso'])
            ->withCount(['publicacoesVinculadas as qtde_comentarios'])
            ->whereNull('id_publicacao_vinculada')
            ->where('id_usuario', $idUsuario)
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->limit($limite)
            ->get();
    }

    public function searchVinculadas(int $idPublicacao, int $limite = 10, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return Publicacao::with(['user', 'user.curso'])
            ->withCount(['publicacoesVinculadas as qtde_comentarios'])
            ->where('id_publicacao_vinculada', $idPublicacao)
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->limit($limite)
            ->get();
    }

    public function hasReacao(int $idUsuario, int $idPublicacao): bool
    {
        return PublicacaoReacao::where('id_usuario', $idUsuario)
            ->where('id_publicacao', $idPublicacao)
            ->where('situacao', 'A')
            ->exists();
    }
}
