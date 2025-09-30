<?php

namespace App\Domain\Services\Recomendacao\GrupoEstudo;

use App\Domain\Repository\GrupoEstudo\PublicacaoRepositoryInterface;
use App\Domain\VO\Recomendacao\InteracoesMembro;
use App\Domain\VO\Recomendacao\KeywordsRelevantes;

use Illuminate\Support\Collection;

class RecomendadorService
{
    public function __construct(protected PublicacaoRepositoryInterface $publicacaoRepository) {}

    public function recomendar(KeywordsRelevantes $keywords, InteracoesMembro $interacoes, int $limite): Collection
    {
        $ignoradas = $interacoes->getIdsIgnorados();
        
        $porCurtidas = $this->publicacaoRepository
            ->searchKeywords($interacoes->getMembroIdGrupoEstudo(), $keywords->curtidas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        $porVisualizacao = $this->publicacaoRepository
            ->searchKeywords($interacoes->getMembroIdGrupoEstudo(),$keywords->visualizadas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $ignoradas));

        return (new Collection())->concat($porCurtidas)->concat($porVisualizacao)->unique('id');
    }

    public function preencherComPopulares(Collection $recomendadas, InteracoesMembro $interacoes, int $limite): Collection
    {
        if ($recomendadas->count() >= $limite) return $recomendadas;

        $faltando = $limite - $recomendadas->count();
        $populares = $this->publicacaoRepository
            ->searchMostPopularPublicacoes($interacoes->getMembroIdGrupoEstudo(), $interacoes->getIdsIgnorados(), $faltando * 2)
            ->filter(fn($p) => !in_array($p->id, $interacoes->getIdsIgnorados()));

        return $recomendadas->concat($populares)->unique('id');
    }
}
