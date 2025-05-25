<?php

namespace App\Domain\Repository;

interface VisualizacaoRepositoryInterface
{
    public function store(int $idPublicacao, int $idUsuario): void;
    public function findByUser(int $idUsuario): array;
}
