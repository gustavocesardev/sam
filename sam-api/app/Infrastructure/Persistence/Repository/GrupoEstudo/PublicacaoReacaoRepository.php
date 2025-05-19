<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Model\GrupoEstudo\PublicacaoReacao;
use App\Domain\Repository\ReacaoRepositoryInterface;

class PublicacaoReacaoRepository implements ReacaoRepositoryInterface
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): ?PublicacaoReacaoAbstract
    {
        return PublicacaoReacao::where('id_publicacao', $idPublicacao)
                               ->where('id_membro', $idUsuario)
                               ->first();
    }

    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void
    {
        PublicacaoReacao::create([
            'id_publicacao' => $idPublicacao,
            'id_membro' => $idUsuario,
        ]);
    }
}
