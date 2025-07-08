<?php

namespace App\Domain\Services\Recomendacao;

use App\Domain\Repository\Abstract\PublicacaoRepositoryAbstract;
use App\Domain\VO\Recomendacao\InteracoesUsuario;
use Illuminate\Database\Eloquent\Collection;

class FeedService
{
    public function __construct(protected PublicacaoRepositoryAbstract $publicacaoRepository) {}

    public function ordenar(Collection $recomendadas, InteracoesUsuario $interacoes): Collection
    {
        $recomendadas->each(fn($p) => $p->prioridade = 1);

        $rebaixadas = $this->publicacaoRepository->searchByIds($interacoes->getIdsIgnorados());
        $rebaixadas->each(fn($p) => $p->prioridade = 0);

        return $recomendadas
            ->concat($rebaixadas)
            ->unique('id')
            ->sortByDesc(fn($p) => ($p->prioridade * 1000000) + ($p->likes + $p->views))
            ->values();
    }
}
