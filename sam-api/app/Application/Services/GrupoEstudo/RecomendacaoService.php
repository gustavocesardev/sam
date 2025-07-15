<?php

namespace App\Application\Services\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;

use App\Domain\Services\Recomendacao\GrupoEstudo\KeywordService;
use App\Domain\Services\Recomendacao\GrupoEstudo\MembroInteracoesService;
use App\Domain\Services\Recomendacao\GrupoEstudo\FeedService;
use App\Domain\Services\Recomendacao\GrupoEstudo\RecomendadorService;
use App\Domain\VO\Recomendacao\InteracoesMembro;

use Illuminate\Support\Collection;

class RecomendacaoService
{
    public function __construct(
        protected MembroInteracoesService $coletor,
        protected KeywordService $extrator,
        protected RecomendadorService $recomendador,
        protected FeedService $ordenador
    ) {}

    public function recomendarFeed(Membro $membro, int $limite = 10): Collection
    {
        $interacoes = $this->coletor->collectInteracoes($membro);
        return $this->filtrarPorConteudo($interacoes, $limite);
    }

    private function filtrarPorConteudo(InteracoesMembro $interacoes, int $limite): Collection
    {
        $keywords = $this->extrator->extractKeywordsByInteracoes($interacoes);
        $recomendadas = $this->recomendador->recomendar($keywords, $interacoes, $limite);
        $completado = $this->recomendador->preencherComPopulares($recomendadas, $interacoes, $limite);
        $feedFinal = $this->ordenador->ordenar($completado, $interacoes);

        return $feedFinal;
    }
}
