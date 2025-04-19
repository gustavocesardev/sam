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
            'razao_social.required' => 'O atributo razão social é obrigatório.',
            'razao_social.string'   => 'O atributo razão social deve ser um texto.',
            'razao_social.max'      => 'O atributo razão social deve ter no máximo 70 caracteres.',

            'tipo_instituicao.required' => 'O tipo de instituição é obrigatório.',
            'tipo_instituicao.string'   => 'O tipo de instituição deve ser um texto.',
            'tipo_instituicao.in'       => 'O tipo de instituição deve ser "PUB" (Pública) ou "PRI" (Privada).',

            'tipo_logradouro.required' => 'O tipo de logradouro é obrigatório.',
            'tipo_logradouro.string'   => 'O tipo de logradouro deve ser um texto.',
            'tipo_logradouro.max'      => 'O tipo de logradouro deve ter no máximo 100 caracteres.',

            'logradouro.required' => 'O logradouro é obrigatório.',
            'logradouro.string'   => 'O logradouro deve ser um texto.',
            'logradouro.max'      => 'O logradouro deve ter no máximo 100 caracteres.',

            'numero.required' => 'O número é obrigatório.',
            'numero.integer'  => 'O número deve ser um inteiro.',
            'numero.min'      => 'O número deve ser um valor positivo.',

            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.string'   => 'A cidade deve ser um texto.',
            'cidade.max'      => 'A cidade deve ter no máximo 70 caracteres.',

            'codigo_municipio.required' => 'O código do município é obrigatório.',
            'codigo_municipio.integer'  => 'O código do município deve ser um inteiro.',
            'codigo_municipio.min'      => 'O código do município deve ser um valor positivo.',

            'uf.required' => 'A unidade federativa (UF) é obrigatória.',
            'uf.string'   => 'A unidade federativa (UF) deve ser um texto.',
            'uf.size'     => 'A unidade federativa (UF) deve ter exatamente 2 caracteres.',

            'dominio_email_institucional.required' => 'O domínio de e-mail institucional é obrigatório.',
            'dominio_email_institucional.string'   => 'O domínio de e-mail institucional deve ser um texto.',
            'dominio_email_institucional.max'      => 'O domínio de e-mail institucional deve ter no máximo 100 caracteres.',

            'imagem.string' => 'A imagem deve ser um texto.',
            'imagem.max'    => 'A imagem deve ter no máximo 150 caracteres.',
        ];
    }
}
