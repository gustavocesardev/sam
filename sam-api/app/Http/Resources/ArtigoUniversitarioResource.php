<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
        $periodoAnoAtual = ($this->usuario->ano_inicio_curso -  Carbon::now()->year) + 1;
        $periodoAnoAtual = $periodoAnoAtual > 0 ? $periodoAnoAtual : 1;

        return [
            'id'              => $this->id,
            'id_usuario'      => $this->id_usuario,
            'nome'            => $this->usuario->name,
            'ano_curso'       => "{$periodoAnoAtual}° {$this->usuario->curso->nome_curso}",
            'titulo'          => $this->titulo,
            'palavras_chave'  => $this->palavras_chave,
            'conteudo'        => $this->conteudo,
            'pdf'             => $this->pdf,
            'publicaco_em'    => 'Publicado em '.$this->created_at->format('d/m/Y'),
            'created_at'      => $this->created_at->format('\à\s\ H:i \e\m d/m/Y')
        ];
    }
}
