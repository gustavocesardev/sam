<?php

namespace App\Http\Resources\GrupoEstudo;

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
        return [
            'id_grupo_estudo' => $this->id,
            'id_curso'        => $this->id_curso,
            'id_usuario'      => $this->id_usuario,
            'nome_grupo'      => $this->nome_grupo,
            'descricao'       => $this->descricao,
            'hashtags'        => $this->hashtags,
            'imagem'          => $this->imagem,
            'imagem_header'   => $this->imagem_header,
            'qtde_membros'     => $this->qtde_membros_ativos    
        ];
    }
}
