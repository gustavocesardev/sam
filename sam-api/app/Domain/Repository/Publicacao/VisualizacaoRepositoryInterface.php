<?php

namespace App\Domain\Repository\Publicacao;

use App\Domain\Repository\Abstract\VisualizacaoRepositoryAbstract;

interface VisualizacaoRepositoryInterface extends VisualizacaoRepositoryAbstract
{
    public function store(int $idPublicacao, int $idUsuario): void;
    public function findByUser(int $idUsuario): array;
    public function findByUserAndCurso(int $idUsuario, int $idCurso): array;
}
