<?php

namespace App\Domain\Services\Recomendacao;

use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\VO\Recomendacao\InteracoesUsuario;
use App\Domain\VO\Recomendacao\KeywordsRelevantes;
use Illuminate\Database\Eloquent\Collection;

class RecomendadorPublicacoesService
{
    public function __construct(protected PublicacaoRepositoryInterface $publicacaoRepository) {}

    public function recomendar(KeywordsRelevantes $keywords, InteracoesUsuario $interacoes, int $limite): Collection
    {
        $ignoradas = $interacoes->getIdsIgnorados();

        $porCurtidas = $this->publicacaoRepository
            ->searchKeywords($keywords->curtidas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        $porVisualizacao = $this->publicacaoRepository
            ->searchKeywords($keywords->visualizadas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        return (new Collection())->concat($porCurtidas)->concat($porVisualizacao)->unique('id');
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

    public function preencherComPopulares(Collection $recomendadas, InteracoesUsuario $interacoes, int $limite): Collection
    {
        if ($recomendadas->count() >= $limite) return $recomendadas;

        $faltando = $limite - $recomendadas->count();
        $populares = $this->publicacaoRepository
            ->searchMostPopularPublicacoes($interacoes->getIdsIgnorados(), $faltando * 2)
            ->filter(fn($p) => !in_array($p->id, $interacoes->getIdsIgnorados()));

        return $recomendadas->concat($populares)->unique('id');
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
