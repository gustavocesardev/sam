<?php

namespace App\Infrastructure\Persistence\Repository\Publicacao;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Model\Publicacao\PublicacaoReacao;
use App\Domain\Repository\ReacaoRepositoryInterface;

class PublicacaoReacaoRepository implements ReacaoRepositoryInterface
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): ?PublicacaoReacaoAbstract
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

    public function findByUser(int $idUsuario): array
    {
        return PublicacaoReacao::where('id_usuario', $idUsuario)->get()->all();
    }

    public function findByUserAndCurso(int $idUsuario, int $idCurso): array
    {
        return PublicacaoReacao::where('id_usuario', $idUsuario)
            ->whereHas('publicacao.user.curso', function ($query) use ($idCurso) {
                $query->where('id_curso', $idCurso);
            })
            ->get()
            ->all();
    }
}
