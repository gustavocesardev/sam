<?php

namespace App\Http\Requests\Store\GrupoEstudo;

use Illuminate\Foundation\Http\FormRequest;

class MembroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario'       => ['required', 'integer'],
            'id_grupo_estudo'  => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required'      => 'O atributo id_usuario é obrigatório.',
            'id_usuario.integer'       => 'O atributo id_usuario deve ser um número inteiro.',

            'id_grupo_estudo.required' => 'O atributo id_grupo_estudo é obrigatório.',
            'id_grupo_estudo.integer'  => 'O atributo id_grupo_estudo deve ser um número inteiro.',
        ];
    }
}
