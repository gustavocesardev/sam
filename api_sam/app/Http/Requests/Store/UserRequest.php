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
            'foto_perfil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'biografia'   => ['required', 'string', 'max:500'],

            'ano_inicio_curso' => ['nullable', 'integer', 'min:1', 'digits:4'],
            'ano_fim_curso'    => ['nullable', 'integer', 'min:1', 'digits:4'],

            'situacao'    => ['nullable', 'in:A,I'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é uma atributo obrigatório.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',

            'foto_perfil.image' => 'O arquivo enviado deve ser uma imagem.',
            'foto_perfil.mimes' => 'A imagem deve estar no formato JPG, JPEG ou PNG.',
            'foto_perfil.max'   => 'A imagem não pode ter mais que 2MB.',

            'biografia.required' => 'A biografia é um atributo obrigatório.',
            'biografia.max'      => 'A biografia deve ter no máximo 255 caracteres.',

            'ano_inicio_curso.integer' => 'O ano de início do curso deve ser um número inteiro.',
            'ano_inicio_curso.min'     => 'O ano de início do curso deve ser um valor positivo.',
            'ano_inicio_curso.max'     => 'O ano de início do curso não pode ter mais que 4 dígitos.',

            'ano_fim_curso.integer' => 'O ano de término do curso deve ser um número inteiro.',
            'ano_fim_curso.min'     => 'O ano de término do curso deve ser um valor positivo.',
            'ano_fim_curso.max'     => 'O ano de término do curso não pode ter mais que 4 dígitos.',
            
            'situacao.in'   => 'A situação deve ser A (Ativo) ou I (Inativo).',
        ];
    }
}
