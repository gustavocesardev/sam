<?php

namespace App\Domain\Services\Recomendacao\Publicacao;

use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\VO\Recomendacao\InteracoesUsuario;

use Illuminate\Support\Collection;

class FeedService
{
    public function __construct(protected PublicacaoRepositoryInterface $publicacaoRepository) {}

    public function ordenar(Collection $recomendadas, InteracoesUsuario $interacoes): Collection
    {
        $recomendadas->each(fn($p) => $p->prioridade = 1);

        $rebaixadas = $this->publicacaoRepository->searchByIds($interacoes->getIdsIgnorados());
        $rebaixadas->each(fn($p) => $p->prioridade = 0);

        return $recomendadas
            ->concat($rebaixadas)
            ->unique('id')
            ->sortByDesc(fn($p) => ($p->prioridade * 1000000) + ($p->qtde_curtidas + $p->qtde_visualizacoes))
            ->values();
    }
}
