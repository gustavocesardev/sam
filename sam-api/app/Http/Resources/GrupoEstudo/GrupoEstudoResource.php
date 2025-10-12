<?php

namespace App\Http\Resources\GrupoEstudo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupoEstudoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $periodoAnoAtual = (Carbon::now()->year - $this->user->curso->ano_inicio_curso) + 1;
        $periodoAnoAtual = $periodoAnoAtual > 0 ? $periodoAnoAtual : 1;

        return [
            'id_grupo_estudo' => $this->id,
            'id_membro'       => $this->id_membro,
            'id_curso'        => $this->id_curso,
            'id_usuario'      => $this->id_usuario,
            'nome'            => $this->user->name,
            'curso'           => "{$periodoAnoAtual}° {$this->user->curso->nome_curso}",
            'nome_grupo'      => $this->nome_grupo,
            'descricao'       => $this->descricao,
            'hashtags'        => $this->hashtags,
            'imagem'          => $this->imagem,
            'imagem_header'   => $this->imagem_header,
            'qtde_membros'    => $this->qtde_membros_ativos,
            'criado_em'       => $this->created_at->format('\à\s\ H:i \e\m d/m/Y'),
        ];
    }
}
