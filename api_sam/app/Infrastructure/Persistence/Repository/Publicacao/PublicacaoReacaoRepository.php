<?php

namespace App\Infrastructure\Persistence\Repository\Publicacao;

use App\Domain\Model\Abstract\AbstractPublicacaoReacao;
use App\Domain\Model\Publicacao\PublicacaoReacao;
use App\Domain\Repository\ReacaoRepositoryInterface;

class PublicacaoReacaoRepository implements ReacaoRepositoryInterface
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): ?AbstractPublicacaoReacao
    {
        return PublicacaoReacao::where('id_publicacao', $idPublicacao)
                               ->where('id_usuario', $idUsuario)
                               ->first();
    }

    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void
    {
        PublicacaoReacao::create([
            'id_publicacao' => $idPublicacao,
            'id_usuario' => $idUsuario,
        ]);
    }
}
