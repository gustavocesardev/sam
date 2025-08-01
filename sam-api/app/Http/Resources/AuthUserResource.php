<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $periodoAnoAtual = ($this->ano_inicio_curso -  Carbon::now()->year) + 1;
        $periodoAnoAtual = $periodoAnoAtual > 0 ? $periodoAnoAtual : 1;

        return [
            'id'    => $this->id,
            'email' => $this->email,
            
            'curso' => [
                'id_curso' => $this->id_curso,
                'periodo'  => $periodoAnoAtual,
                'nome_curso' => $this->curso->nome_curso,
            ],
            
            'instituicao' => [
                'id_instituicao'      => $this->curso->id_instituicao,
                'nome_instituicao'    => $this->curso->instituicao->razao_social,
                'imagem_instituicao'  => $this->curso->instituicao->imagem,
                'dominio_instituicao' => $this->curso->instituicao->dominio_email_institucional,
            ]
        ];
    }
}
