<?php

namespace App\Http\Resources;

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
        return [
            'id_publicacao'           => $this->id,
            'id_publicacao_vinculada' => $this->id_publicacao_vinculada,
            'id_usuario'              => $this->id_usuario,
            'texto'                   => $this->texto,
            'imagens'                 => $this->imagens,
            'qtde_curtidas'           => $this->qtde_curtidas,
            'qtde_visualizacoes'      => $this->qtde_visualizacoes
        ];
    }
}
