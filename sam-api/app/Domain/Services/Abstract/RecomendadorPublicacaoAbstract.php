<?php

namespace App\Domain\Services\Abstract;

use App\Domain\Repository\Abstract\PublicacaoRepositoryAbstract;
use App\Domain\VO\Recomendacao\Abstract\Interacoes;
use App\Domain\VO\Recomendacao\KeywordsRelevantes;
use Illuminate\Database\Eloquent\Collection;

abstract class RecomendadorPublicacaoAbstract
{
    public function __construct(protected PublicacaoRepositoryAbstract $publicacaoRepository) {}

    public function recomendar(KeywordsRelevantes $keywords, Interacoes $interacoes, int $limite): Collection
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

    public function preencherComPopulares(Collection $recomendadas, Interacoes $interacoes, int $limite): Collection
    {
        if ($recomendadas->count() >= $limite) return $recomendadas;

        $faltando = $limite - $recomendadas->count();
        $populares = $this->publicacaoRepository
            ->searchMostPopularPublicacoes($interacoes->getIdsIgnorados(), $faltando * 2)
            ->filter(fn($p) => !in_array($p->id, $interacoes->getIdsIgnorados()));

        return $recomendadas->concat($populares)->unique('id');
    }
}