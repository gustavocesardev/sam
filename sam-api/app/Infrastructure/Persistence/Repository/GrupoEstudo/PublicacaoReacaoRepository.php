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

    public function findByUser(int $idMembro): array
    {
        return PublicacaoReacao::where('id_membro', $idMembro)->get()->all();
    }

    public function findByUserAndCurso(int $idUsuario, int $idCurso): array
    {
         return PublicacaoReacao::whereHas('membro', function ($query) use ($idUsuario, $idCurso) {
            $query->where('id_usuario', $idUsuario)
                ->where('id_curso', $idCurso);
        })->get()->all();
    }
}
