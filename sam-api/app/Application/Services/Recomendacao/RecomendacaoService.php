<?php

namespace App\Application\Services\Recomendacao;

use App\Domain\Repository\KeywordRepositoryInterface;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RecomendacaoService
{
    public function __construct(
        protected KeywordRepositoryInterface $keywordRepository,
        protected PublicacaoRepositoryInterface $publicacaoRepository,
        protected VisualizacaoRepositoryInterface $visualizacaoRepository,
        protected ReacaoRepositoryInterface $reacaoRepository
    ) {}

    /**
     * Gera um feed de publicações recomendadas para um usuário específico,
     * combinando publicações relacionadas às keywords de conteúdo curtido e visualizado,
     * além de incluir publicações populares e manter as já visualizadas/curtidas ao final da listagem.
     *
     * O algoritmo prioriza publicações associadas às keywords das curtidas, seguido das visualizadas,
     * e preenche o feed com publicações populares caso o limite não seja atingido.
     * As publicações já curtidas ou visualizadas pelo usuário são mantidas no feed,
     * mas aparecem por último, com prioridade menor.
     *
     * @param int $idUsuario ID do usuário para quem as recomendações serão geradas.
     * @param int $limite Quantidade máxima de publicações recomendadas a retornar (padrão 10).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *         Uma coleção de publicações recomendadas ordenadas pela prioridade
     *         e popularidade (likes + visualizações).
     *
     * Fluxo da lógica:
     * 1. Busca publicações curtidas e visualizadas pelo usuário.
     * 2. Extrai keywords dessas publicações, separando curtidas e visualizadas.
     * 3. Busca publicações relacionadas às keywords das curtidas e visualizadas,
     *    excluindo as já curtidas/visualizadas (rebaixadas).
     * 4. Combina as publicações recomendadas, dando prioridade às curtidas.
     * 5. Se necessário, adiciona publicações populares para completar o limite.
     * 6. Anexa as publicações já curtidas/visualizadas no final do feed.
     * 7. Ordena o feed final para exibir primeiro as recomendações e depois os rebaixados,
     *    ambos ordenados pela soma de likes e visualizações.
     */
    public function recomendarFeed(int $idUsuario, int $limite = 10): Collection
    {
        // 1. Publicações curtidas e visualizadas pelo usuário
        $reacoes = $this->reacaoRepository->findByUser(idUsuario: $idUsuario);
        $visualizacoes = $this->visualizacaoRepository->findByUser($idUsuario);

        $curtidasIds = array_map(fn($r) => $r->id_publicacao, $reacoes);
        $visualizadasIds = array_map(fn($v) => $v->id_publicacao, $visualizacoes);

        // Lista de IDs que serão rebaixados (não removidos)
        $publicacoesIgnoradas = array_unique(array_merge($curtidasIds, $visualizadasIds));

        // 2. Coletar keywords das publicações curtidas
        $keywordsCurtidas = [];
        foreach ($curtidasIds as $id) {
            $keywordsCurtidas = array_merge($keywordsCurtidas, $this->keywordRepository->findByPublicacao($id));
        }

        // 3. Coletar keywords das visualizadas (sem repetir keywords das curtidas)
        $keywordsVisualizadas = [];
        foreach ($visualizadasIds as $id) {
            $keywordsVisualizadas = array_merge($keywordsVisualizadas, $this->keywordRepository->findByPublicacao($id));
        }

        $keywordsCurtidas = array_unique($keywordsCurtidas);
        $keywordsVisualizadas = array_diff(array_unique($keywordsVisualizadas), $keywordsCurtidas);

        // 4. Buscar publicações relacionadas às keywords, excluindo rebaixadas
        $porCurtidas = $this->publicacaoRepository
            ->searchKeywords($keywordsCurtidas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $publicacoesIgnoradas));

        $porVisualizacao = $this->publicacaoRepository
            ->searchKeywords($keywordsVisualizadas, $limite * 2)
            ->filter(fn($p) => !in_array($p->id, $publicacoesIgnoradas));

        // 5. Combinar com prioridade (curtidas > visualizações)
        $recomendadas = (new Collection())
            ->concat($porCurtidas)
            ->concat($porVisualizacao)
            ->unique('id');

        // 6. Se não atingir o limite, preencher com publicações populares
        if ($recomendadas->count() < $limite)
        {
            $faltando = $limite - $recomendadas->count();
            $populares = $this->publicacaoRepository
                ->searchMostPopularPublicacoes($publicacoesIgnoradas, $faltando * 2)
                ->filter(fn($p) => !in_array($p->id, $publicacoesIgnoradas))
                ->unique('id');

            $recomendadas = $recomendadas->concat($populares)->unique('id');
        }

        // 7. Adicionar as publicações já visualizadas/curtidas no final (rebaixadas)
        $publicacoesRebaixadas = $this->publicacaoRepository->searchByIds($publicacoesIgnoradas);

        // Marcar prioridade (1 = recomendadas, 0 = rebaixadas)
        $recomendadas->each(fn($p) => $p->prioridade = 1);
        $publicacoesRebaixadas->each(fn($p) => $p->prioridade = 0);

        // Combinar tudo e ordenar: recomendadas primeiro, rebaixadas no fim
        $feedFinal = $recomendadas
            ->concat($publicacoesRebaixadas)
            ->unique('id')
            ->sortByDesc(fn($p) => ($p->prioridade ?? 0) * 1000000 + (($p->likes ?? 0) + ($p->views ?? 0)))
            ->values();

        return $feedFinal;
    }
}
