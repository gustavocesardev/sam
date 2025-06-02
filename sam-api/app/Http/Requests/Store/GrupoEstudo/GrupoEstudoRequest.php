<?php

namespace App\Http\Requests\Store\GrupoEstudo;

use Illuminate\Foundation\Http\FormRequest;

class GrupoEstudoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_curso'       => ['required', 'integer',],
            'id_usuario'     => ['required', 'integer',],
            'nome_grupo'     => ['required', 'string', 'max:150'],
            'descricao'      => ['required', 'string'],
            'hashtags'       => ['required', 'string', 'max:150'],
            'imagem'         => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'imagem_header'  => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_curso.required'  => 'O atributo id_curso é obrigatório.',
            'id_curso.integer'   => 'O atributo id_curso deve ser um número inteiro.',

            'id_usuario.required'  => 'O atributo id_usuario é obrigatório.',
            'id_usuario.integer'   => 'O atributo id_usuario deve ser um número inteiro.',

            'nome_grupo.required' => 'O atributo nome_grupo é obrigatório.',
            'nome_grupo.string'   => 'O atributo nome_grupo deve ser um texto.',
            'nome_grupo.max'      => 'O nome do grupo pode ter no máximo 150 caracteres.',

            'descricao.required' => 'A descrição do grupo é obrigatória.',
            'descricao.string'   => 'A descrição deve ser um texto.',

            'hashtags.required' => 'O atributo hashtags é obrigatório.',
            'hashtags.string'   => 'O atributo hashtags deve ser um texto.',
            'hashtags.max'      => 'O atributo hashtags pode ter no máximo 150 caracteres.',

            'imagem.required' => 'O atributo imagem é obrigatório.',
            'imagem.image'    => 'O arquivo enviado deve ser uma imagem.',
            'imagem.mimes'    => 'A imagem do grupo deve estar no formato JPG, JPEG ou PNG.',

            'imagem_header.required' => 'O atributo imagem_header é obrigatório.',
            'imagem_header.image'    => 'O arquivo enviado para o header deve ser uma imagem.',
            'imagem_header.mimes'    => 'O header do grupo deve estar no formato JPG, JPEG ou PNG.',
        ];
    }
}
