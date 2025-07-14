<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ArtigoUniversitarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario'     => ['required', 'integer'],
            'titulo'         => ['required', 'string', 'max:250'],
            'palavras_chave' => ['nullable', 'string', 'max:250'],
            'conteudo'       => ['required', 'string'],
            'pdf'            => ['nullable', File::types(['pdf'])->max(10240)],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'O atributo id_usuario é obrigatório.',
            'id_usuario.integer'  => 'O atributo id_usuario deve ser um número inteiro.',

            'titulo.required' => 'O atributo titulo é obrigatório.',
            'titulo.string'   => 'O atributo titulo deve ser um texto.',
            'titulo.max'      => 'O atributo titulo deve ter no máximo 250 caracteres.',

            'palavras_chave.string' => 'O atributo palavras_chave deve ser um texto.',
            'palavras_chave.max'    => 'O atributo palavras_chave deve ter no máximo 250 caracteres.',

            'conteudo.required' => 'O atributo conteudo é obrigatório.',
            'conteudo.string'   => 'O atributo conteudo deve ser um texto.',

            'pdf.file'      => 'O arquivo enviado deve ser um arquivo.',
            'pdf.mimes'     => 'O arquivo deve ser um PDF.',
            'pdf.max'       => 'O PDF deve ter no máximo 10MB.',
        ];
    }
}