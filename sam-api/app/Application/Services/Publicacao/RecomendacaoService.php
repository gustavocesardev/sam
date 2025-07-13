<?php

namespace App\Application\Services\Publicacao;

use App\Domain\Model\User;

use App\Domain\Services\Recomendacao\Publicacao\FeedService;
use App\Domain\Services\Recomendacao\Publicacao\KeywordService;
use App\Domain\Services\Recomendacao\Publicacao\RecomendadorService;
use App\Domain\Services\Recomendacao\Publicacao\UserInteracoesService;
use App\Domain\VO\Recomendacao\InteracoesUsuario;

use Illuminate\Database\Eloquent\Collection;

class RecomendacaoService
{
    public function __construct(
        protected UserInteracoesService $coletor,
        protected KeywordService $extrator,
        protected RecomendadorService $recomendador,
        protected FeedService $ordenador
    ) {}

    public function recomendarFeed(User $usuario, int $limite = 10): Collection
    {
        $interacoes = $this->coletor->collectInteracoes($usuario);
        return $this->filtrarPorConteudo($interacoes, $limite);
    }

    public function recomendarFeedPorCurso(User $usuario, int $limite = 10): Collection
    {
        $interacoes = $this->coletor->collectInteracoesByCurso($usuario);
        return $this->filtrarPorConteudoByCurso($interacoes, $limite);
    }

    private function filtrarPorConteudo(InteracoesUsuario $interacoes, int $limite): Collection
    {
        $keywords = $this->extrator->extractKeywordsByInteracoes($interacoes);
        $recomendadas = $this->recomendador->recomendar($keywords, $interacoes, $limite);
        $completado = $this->recomendador->preencherComPopulares($recomendadas, $interacoes, $limite);
        $feedFinal = $this->ordenador->ordenar($completado, $interacoes);

        return $feedFinal;
    }

    private function filtrarPorConteudoByCurso(InteracoesUsuario $interacoes, int $limite): Collection
    {
        $keywords = $this->extrator->extractKeywordsByInteracoes($interacoes);
        $recomendadas = $this->recomendador->recomendarByCurso($keywords, $interacoes, $limite);
        $completado = $this->recomendador->preencherComPopularesByCurso($recomendadas, $interacoes, $limite);
        $feedCurso = $this->ordenador->ordenar($completado, $interacoes);

        return $feedCurso;
    }
}
