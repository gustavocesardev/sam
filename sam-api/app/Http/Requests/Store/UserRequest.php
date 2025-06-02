<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ou valida com policy, se necessário
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'biografia'   => ['required', 'string', 'max:500'],
            
            'ano_inicio_curso' => ['required', 'integer', 'min:1', 'digits:4'],
            'ano_fim_curso'    => ['required', 'integer', 'min:1', 'digits:4'],

            'foto_perfil' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'situacao'    => ['nullable', 'in:A,I'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O atributo nome é obrigatório.',
            'name.max' => 'O atributo nome deve ter no máximo 255 caracteres.',

            'foto_perfil.image' => 'O atributo foto_perfil enviado deve ser uma imagem.',
            'foto_perfil.mimes' => 'A imagem deve estar no formato JPG, JPEG ou PNG.',

            'biografia.required' => 'O atributo biografia é obrigatório.',
            'biografia.max'      => 'O atributo biografia deve ter no máximo 255 caracteres.',

            'ano_inicio_curso.required' => 'O atributo ano_inicio_curso é obrigatório.',
            'ano_inicio_curso.integer'  => 'O atributo ano_inicio_curso deve ser um número inteiro.',
            'ano_inicio_curso.min'      => 'O atributo ano_inicio_curso deve ser um valor positivo.',
            'ano_inicio_curso.max'      => 'O atributo ano_inicio_curso não pode ter mais que 4 dígitos.',

            'ano_fim_curso.required' => 'O atributo ano_fim_curso é obrigatório.',
            'ano_fim_curso.integer'  => 'O atributo ano_fim_curso deve ser um número inteiro.',
            'ano_fim_curso.min'      => 'O atributo ano_fim_curso deve ser um valor positivo.',
            'ano_fim_curso.max'      => 'O atributo ano_fim_curso não pode ter mais que 4 dígitos.',
            
            'situacao.in'   => 'O atributo situacao deve ser A (Ativo) ou I (Inativo).',
        ];
    }
}
