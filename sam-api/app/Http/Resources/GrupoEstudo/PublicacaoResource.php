<?php

namespace App\Http\Resources\GrupoEstudo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicacaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $periodoAnoAtual = ($this->membro->user->ano_inicio_curso -  Carbon::now()->year) + 1;
        $periodoAnoAtual = $periodoAnoAtual > 0 ? $periodoAnoAtual : 1;

        return [
            'id_publicacao'           => $this->id,
            'id_publicacao_vinculada' => $this->id_publicacao_vinculada,
            'id_membro'               => $this->id_membro,
            'foto_usuario'            => $this->membro->user->foto_perfil,
            'nome'                    => $this->membro->user->name,
            'curso'                   => "{$periodoAnoAtual}° {$this->membro->user->curso->nome_curso}",     
            'texto'                   => $this->texto,
            'imagens'                 => $this->imagens,
            'criado_em'               => $this->created_at->format('\à\s\ H:i \e\m d/m/Y'),
            'qtde_curtidas'           => $this->qtde_curtidas,
            'qtde_visualizacoes'      => $this->qtde_visualizacoes,
            'qtde_comentarios'        => $this->qtde_comentarios
        ];
    }
}
