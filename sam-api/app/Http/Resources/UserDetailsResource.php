<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'id_curso'          => $this->id_curso,
            'name'              => $this->name,
            'email'             => $this->email,
            'biografia'         => $this->biografia,
            'situacao'          => $this->situacao,
            'ano_inicio_curso'  => $this->ano_inicio_curso,
            'ano_fim_curso'     => $this->ano_fim_curso,
            'foto_perfil'       => $this->foto_perfil,
            'created_at'        => $this->created_at->format('\à\s\ H:i \e\m d/m/Y'),
            'updated_at'        => $this->updated_at->format('\à\s\ H:i \e\m d/m/Y'),

            'curso' => [
                'nome' => $this->curso->nome_curso,
            ],

            'contadores' => [
                'artigos' => $this->artigos_count,
                'publicacoes' => $this->publicacoes_count
            ]
        ];
    }
}
