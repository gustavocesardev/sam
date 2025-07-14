<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class FormularioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario'   => ['required', 'integer'],
            'titulo'       => ['required', 'string', 'max:100'],
            'descricao'    => ['required', 'string', 'max:250'],
            'tipo'         => ['required', 'string', 'in:AC,GE'],
            'situacao'     => ['string', 'in:A,I'],
            'link_forms'   => ['required', 'string', 'url', 'max:500'],
            'data_limite'  => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'O atributo id_usuario é obrigatório.',
            'id_usuario.integer'  => 'O atributo id_usuario deve ser um número inteiro.',

            'titulo.required' => 'O atributo titulo é obrigatório.',
            'titulo.string'   => 'O atributo titulo deve ser um texto.',
            'titulo.max'      => 'O atributo titulo deve ter no máximo 100 caracteres.',

            'descricao.required' => 'O atributo descricao é obrigatório.',
            'descricao.string'   => 'O atributo descricao deve ser um texto.',
            'descricao.max'      => 'O atributo descricao deve ter no máximo 250 caracteres.',

            'tipo.required' => 'O atributo tipo é obrigatório.',
            'tipo.string'   => 'O atributo tipo deve ser um texto.',
            'tipo.in'       => 'O atributo tipo deve ser "AC" (Acadêmico) ou "GE" (Geral).',

            'situacao.string'   => 'O atributo situacao deve ser um texto.',
            'situacao.in'       => 'O atributo situacao deve ser "A" (Ativo) ou "I" (Inativo).',

            'link_forms.required' => 'O atributo link_forms é obrigatório.',
            'link_forms.string'   => 'O atributo link_forms deve ser um texto.',
            'link_forms.url'      => 'O atributo link_forms deve ser uma URL válida.',
            'link_forms.max'      => 'O atributo link_forms deve ter no máximo 500 caracteres.',

            'data_limite.required'       => 'O atributo data_limite é obrigatório.',
            'data_limite.date'           => 'O atributo data_limite deve ser uma data válida.',
            'data_limite.after_or_equal' => 'O atributo data_limite deve ser igual ou posterior à data de hoje.',
        ];
    }
}
