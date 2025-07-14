<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtigoUniversitarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'id_usuario'      => $this->id_usuario,
            'titulo'          => $this->titulo,
            'palavras_chave'  => $this->palavras_chave,
            'conteudo'        => $this->conteudo,
            'pdf'             => $this->pdf,
            'created_at'      => $this->created_at->format('\à\s\ H:i \e\m d/m/Y')
        ];
    }
}
