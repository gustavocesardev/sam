<?php

namespace App\Http\Resources;

use App\Domain\Enums\TipoInstituicao;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstituicaoResource extends JsonResource
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
            'id'               => $this->id,
            'razao_social'     => $this->razao_social,
            'tipo_instituicao' => TipoInstituicao::from($this->tipo_instituicao),
            'tipo_logradouro'  => $this->tipo_logradouro,
            'logradouro'       => $this->logradouro,
            'numero'           => $this->numero,
            'cidade'           => $this->cidade,
            'uf'               => $this->uf,
            'dominio'          => $this->dominio_email_institucional,
            'imagem'           => $this->imagem
        ];
    }
}
