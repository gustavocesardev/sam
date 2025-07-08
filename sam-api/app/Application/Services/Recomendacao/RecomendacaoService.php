<?php

namespace App\Application\Services\Recomendacao;

use App\Domain\Model\User;
use App\Domain\Services\KeywordService;
use App\Domain\Services\Recomendacao\FeedService;
use App\Domain\Services\Recomendacao\RecomendadorPublicacoesService;
use App\Domain\Services\Recomendacao\UserInteracoesService;
use App\Domain\VO\Recomendacao\InteracoesUsuario;

use Illuminate\Database\Eloquent\Collection;

class RecomendacaoService
{
    public function __construct(
        protected UserInteracoesService $coletor,
        protected KeywordService $extrator,
        protected RecomendadorPublicacoesService $recomendador,
        protected FeedService $ordenador
    ) {}

    public function recomendarFeed(User $usuario, int $limite = 10): Collection
    {
        $interacoes = $this->coletor->collectPublicacoes($usuario);
        return $this->filtrarPorConteudo($interacoes, $limite);
    }

    public function recomendarFeedPorCurso(User $usuario, int $limite = 10): Collection
    {
        $interacoes = $this->coletor->collectPublicacoesByCurso($usuario);
        return $this->filtrarPorConteudoByCurso($interacoes, $limite);
    }

    private function filtrarPorConteudo(InteracoesUsuario $interacoes, int $limite): Collection
    {
        $keywords = $this->extrator->extrairInteracoesUsuario($interacoes);
        $recomendadas = $this->recomendador->recomendar($keywords, $interacoes, $limite);
        $completado = $this->recomendador->preencherComPopulares($recomendadas, $interacoes, $limite);
        $feedFinal = $this->ordenador->ordenar($completado, $interacoes);

        return $feedFinal;
    }

    private function filtrarPorConteudoByCurso(InteracoesUsuario $interacoes, int $limite): Collection
    {
        $keywords = $this->extrator->extrairInteracoesUsuario($interacoes);
        $recomendadas = $this->recomendador->recomendarByCurso($keywords, $interacoes, $limite);
        $completado = $this->recomendador->preencherComPopularesByCurso($recomendadas, $interacoes, $limite);
        $feedCurso = $this->ordenador->ordenar($completado, $interacoes);

        return $feedCurso;
    }
}
