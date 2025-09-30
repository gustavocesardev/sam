<?php

namespace App\Http\Resources\GrupoEstudo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $periodoAnoAtual = ($this->user->curso->ano_inicio_curso -  Carbon::now()->year) + 1;
        $periodoAnoAtual = $periodoAnoAtual > 0 ? $periodoAnoAtual : 1;

        return [
            'id_membro'        => $this->id,
            'id_usuario'       => $this->id_usuario,
            'nome'             => $this->user->name,
            'foto_perfil'      => $this->user->foto_perfil,
            'curso'            => "{$periodoAnoAtual}° {$this->user->curso->nome_curso}",
            'id_grupo_estudo'  => $this->id_grupo_estudo,
            'situacao'         => $this->situacao,
        ];
    }
}
