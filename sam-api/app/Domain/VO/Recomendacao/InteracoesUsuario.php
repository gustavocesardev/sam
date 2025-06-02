<?php

namespace App\Domain\VO\Recomendacao;

use App\Domain\Model\User;

class InteracoesUsuario
{
    public function __construct(
        public User $usuario,
        public array $reacoes,
        public array $visualizacoes
    ) {}

    public function getPublicacoesCurtidasIds(): array
    {
        return array_map(fn($r) => $r->id_publicacao, $this->reacoes);
    }

    public function getPublicacoesVisualizadasIds(): array
    {
        return array_map(fn($v) => $v->id_publicacao, $this->visualizacoes);
    }

    public function getIdUsuario()
    {
        return $this->usuario->id;
    }

    public function getUsuarioIdCurso()
    {
        return $this->usuario->curso->id;
    }

    public function getUsuarioIdInstituicao()
    {
        return $this->usuario->curso->instituicao->id;
    }

    public function getIdsIgnorados(): array
    {
        return array_unique(array_merge(
            $this->getPublicacoesCurtidasIds(),
            $this->getPublicacoesVisualizadasIds()
        ));
    }
}
