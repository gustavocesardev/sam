<?php

namespace App\Application\Contracts\Services\Publicacao;

use App\Domain\Model\Publicacao\Publicacao;
use App\Domain\VO\Auth\AuthenticatedUser;

interface InteracoesPublicacaoServiceInterface
{
    public function registrarVisualizacao(Publicacao $publicacao, AuthenticatedUser $user): void;
    public function adicionarReacao(int $idPublicacao, AuthenticatedUser $user): void;
    public function removerReacao(int $idPublicacao, AuthenticatedUser $user): void;
}