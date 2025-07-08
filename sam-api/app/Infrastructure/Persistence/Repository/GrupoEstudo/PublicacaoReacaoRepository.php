<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Model\GrupoEstudo\PublicacaoReacao;
use App\Domain\Repository\GrupoEstudo\ReacaoRepositoryInterface;

class PublicacaoReacaoRepository implements ReacaoRepositoryInterface
{
    public function findByPublicacaoAndMembro(int $idPublicacao, int $idMembro): ?PublicacaoReacaoAbstract
    {
        return PublicacaoReacao::where('id_publicacao', $idPublicacao)
                               ->where('id_membro', $idMembro)
                               ->first();
    }

    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void
    {
        PublicacaoReacao::create([
            'id_publicacao' => $idPublicacao,
            'id_membro' => $idUsuario,
        ]);
    }

    public function findByMembro(int $idMembro): array
    {
        return PublicacaoReacao::where('id_membro', $idMembro)->get()->all();
    }
}
