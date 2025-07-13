<?php

namespace App\Domain\Services\Recomendacao\Publicacao;

use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\VO\Recomendacao\InteracoesUsuario;
use App\Domain\VO\Recomendacao\KeywordsRelevantes;

use Illuminate\Database\Eloquent\Collection;

class RecomendadorService
{
    public function __construct(protected PublicacaoRepositoryInterface $publicacaoRepository) {}

    public function recomendar(KeywordsRelevantes $keywords, InteracoesUsuario $interacoes, int $limite): Collection
    {
        $ignoradas = $interacoes->getIdsIgnorados();

        $porCurtidas = $this->publicacaoRepository
            ->searchKeywords($interacoes->getUsuarioIdInstituicao(), $keywords->curtidas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        $porVisualizacao = $this->publicacaoRepository
            ->searchKeywords($interacoes->getUsuarioIdInstituicao(), $keywords->visualizadas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        return (new Collection())->concat($porCurtidas)->concat($porVisualizacao)->unique('id');
    }

    public function preencherComPopulares(Collection $recomendadas, InteracoesUsuario $interacoes, int $limite): Collection
    {
        if ($recomendadas->count() >= $limite) return $recomendadas;

        $faltando = $limite - $recomendadas->count();
        $populares = $this->publicacaoRepository
            ->searchMostPopularPublicacoes($interacoes->getUsuarioIdInstituicao(),$interacoes->getIdsIgnorados(), $faltando * 2)
            ->filter(fn($p) => !in_array($p->id, $interacoes->getIdsIgnorados()));

        return $recomendadas->concat($populares)->unique('id');
    }

    public function recomendarByCurso(KeywordsRelevantes $keywords, InteracoesUsuario $interacoes, int $limite): Collection
    {
        $ignoradas = $interacoes->getIdsIgnorados();

        $porCurtidas = $this->publicacaoRepository
            ->searchKeywordsByCurso($interacoes->getUsuarioIdCurso(), $keywords->curtidas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        $porVisualizacao = $this->publicacaoRepository
            ->searchKeywordsByCurso($interacoes->getUsuarioIdCurso(), $keywords->curtidas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        return (new Collection())->concat($porCurtidas)->concat($porVisualizacao)->unique('id');
    }

    public function preencherComPopularesByCurso(Collection $recomendadas, InteracoesUsuario $interacoes, int $limite): Collection
    {
        if ($recomendadas->count() >= $limite) return $recomendadas;

        $faltando = $limite - $recomendadas->count();
        $populares = $this->publicacaoRepository
            ->searchMostPopularPublicacoesByCurso($interacoes->getUsuarioIdCurso(), $interacoes->getIdsIgnorados(), $faltando * 2)
            ->filter(fn($p) => !in_array($p->id, $interacoes->getIdsIgnorados()));

        return $recomendadas->concat($populares)->unique('id');
    }
}
