<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\PublicacaoVisualizacao;
use App\Domain\Repository\VisualizacaoRepositoryInterface;

class VisualizacaoRepository implements VisualizacaoRepositoryInterface
{
    public function store(int $idPublicacao, int $idUsuario): void
    {
        $data = [
            'id_publicacao' => $idPublicacao,
            'id_usuario' => $idUsuario
        ];

        PublicacaoVisualizacao::create($data);
    }
}
