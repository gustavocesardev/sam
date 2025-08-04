<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormularioResource extends JsonResource
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
            'id'           => $this->id,
            'id_usuario'   => $this->id_usuario,
            'nome'         => $this->usuario->name,
            'periodo'      => "{$periodoAnoAtual}° ANO", 
            'curso'        => $this->usuario->curso->nome_curso,
            'titulo'       => $this->titulo,
            'descricao'    => $this->descricao,
            'tipo'         => $this->tipo,
            'situacao'     => $this->situacao,
            'link_forms'   => $this->link_forms,
            'data_limite'  => $this->data_limite->format('d-m-Y'),
            'criado_em'    => $this->created_at->format('\à\s\ H:i \e\m d/m/Y')
        ];
    }
}
