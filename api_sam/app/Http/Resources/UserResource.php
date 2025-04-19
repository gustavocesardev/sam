<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'              => $this->name,
            'email'             => $this->email,
            'biografia'         => $this->biografia,
            'situacao'          => $this->situacao,
            'ano_inicio_curso'  => $this->ano_inicio_curso,
            'ano_fim_curso'     => $this->ano_fim_curso,
            'foto_perfil'       => $this->foto_perfil,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
