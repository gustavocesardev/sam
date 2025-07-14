<?php

namespace App\Http\Requests\Filter;

use Illuminate\Foundation\Http\FormRequest;

class FormularioFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'       => ['nullable', 'string', 'max:100'],
            'descricao'    => ['nullable', 'string'],
            'tipo'         => ['nullable', 'in:AC,GE'],
            'data_limite'  => ['nullable', 'date'],
            'id_curso'     => ['nullable', 'int']
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.string'      => 'O título deve ser um texto.',
            'titulo.max'         => 'O título pode ter no máximo 100 caracteres.',

            'id_curso.integer'  => 'O atributo id_curso deve ser um número inteiro',
            'descricao.string'   => 'A descrição deve ser um texto.',

            'tipo.in'            => 'O tipo deve ser "AC" (Atividade Complementar) ou "GE" (Grupo de Estudo).',

            'data_limite.date'   => 'A data limite deve estar em um formato de data válido (ex: YYYY-MM-DD).',
        ];
    }
}
