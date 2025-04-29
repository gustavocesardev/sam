<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class InstituicaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'razao_social' => ['required', 'string', 'max:70'],
            'tipo_instituicao' => ['required', 'string', 'in:PUB,PRI'],
            'tipo_logradouro' => ['required', 'string', 'max:100'],
            'logradouro' => ['required', 'string', 'max:100'],
            'numero' => ['required', 'integer', 'min:1'],
            'cidade' => ['required', 'string', 'max:70'],
            'codigo_municipio' => ['required', 'integer', 'min:1'],
            'uf' => ['required', 'string', 'size:2'],
            'dominio_email_institucional' => ['required', 'string', 'max:100'],
            'imagem' => ['nullable', 'string', 'max:150']
        ];
    }

    public function messages(): array
    {
        return [
            'razao_social.required' => 'O atributo razao_social é obrigatório.',
            'razao_social.string'   => 'O atributo razao_social deve ser um texto.',
            'razao_social.max'      => 'O atributo razao_social deve ter no máximo 70 caracteres.',

            'tipo_instituicao.required' => 'O atributo tipo_instituicao é obrigatório.',
            'tipo_instituicao.string'   => 'O atributo tipo_instituicao deve ser um texto.',
            'tipo_instituicao.in'       => 'O atributo tipo_instituicao deve ser "PUB" (Pública) ou "PRI" (Privada).',

            'tipo_logradouro.required' => 'O atributo tipo_logradouro é obrigatório.',
            'tipo_logradouro.string'   => 'O atributo tipo_logradouro deve ser um texto.',
            'tipo_logradouro.max'      => 'O atributo tipo_logradouro deve ter no máximo 100 caracteres.',

            'logradouro.required' => 'O atributo logradouro é obrigatório.',
            'logradouro.string'   => 'O atributo logradouro deve ser um texto.',
            'logradouro.max'      => 'O atributo logradouro deve ter no máximo 100 caracteres.',

            'numero.required' => 'O atributo numero é obrigatório.',
            'numero.integer'  => 'O atributo numero deve ser um inteiro.',
            'numero.min'      => 'O atributo numero deve ser um valor positivo.',

            'cidade.required' => 'O atributo cidade é obrigatório.',
            'cidade.string'   => 'O atributo cidade deve ser um texto.',
            'cidade.max'      => 'O atributo cidade deve ter no máximo 70 caracteres.',

            'codigo_municipio.required' => 'O atributo codigo_municipio é obrigatório.',
            'codigo_municipio.integer'  => 'O atributo codigo_municipio deve ser um inteiro.',
            'codigo_municipio.min'      => 'O atributo codigo_municipio deve ser um valor positivo.',

            'uf.required' => 'O atributo uf é obrigatório.',
            'uf.string'   => 'O atributo uf deve ser um texto.',
            'uf.size'     => 'O atributo uf deve ter exatamente 2 caracteres.',

            'dominio_email_institucional.required' => 'O atributo dominio_email_institucional é obrigatório.',
            'dominio_email_institucional.string'   => 'O atributo dominio_email_institucional deve ser um texto.',
            'dominio_email_institucional.max'      => 'O atributo dominio_email_institucional deve ter no máximo 100 caracteres.',

            'imagem.string' => 'O atributo imagem deve ser um texto.',
            'imagem.max'    => 'O atributo imagem deve ter no máximo 150 caracteres.',
        ];
    }
}
