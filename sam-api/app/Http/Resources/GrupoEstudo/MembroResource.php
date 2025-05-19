<?php

namespace App\Http\Resources\GrupoEstudo;

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
        return [
            'id_membro'        => $this->id,
            'id_usuario'       => $this->id_usuario,
            'id_grupo_estudo'  => $this->id_grupo_estudo,
            'situacao'         => $this->situacao,
        ];
    }
}
