<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CursoResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nome_curso'     => $this->nome_curso,
            'duracao_minima' => $this->duracao_minima,
            'duracao_maxima' => $this->duracao_maxima,
            'situacao'       => $this->situacao
        ];  
    }
}
