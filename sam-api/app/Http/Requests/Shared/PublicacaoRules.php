<?php

namespace App\Http\Requests\Shared;

trait PublicacaoRules
{
    public function publicacaoRules(string $usuarioKey = 'id_usuario'): array
    {
        return [
            $usuarioKey => ['required', 'integer'],
            'id_publicacao_vinculada' => ['nullable', 'integer'],
            'texto' => ['required', 'string', 'max:500'],
            'imagens' => ['nullable', 'array', 'max:4'],
            'imagens.*' => ['image', 'mimes:jpg,jpeg,png'],
        ];
    }

    public function publicacaoMessages(string $usuarioKey = 'id_usuario'): array
    {
        return [
            "$usuarioKey.required" => "O atributo $usuarioKey é obrigatório.",
            "$usuarioKey.integer" => "O atributo $usuarioKey deve ser um número inteiro.",

            'id_publicacao_vinculada.integer' => 'O atributo id_publicacao_vinculada deve ser um número inteiro.',

            'texto.required' => 'O atributo texto é obrigatório.',
            'texto.string' => 'O atributo texto deve ser uma string.',
            'texto.max' => 'O atributo texto não pode ter mais que 500 caracteres.',

            'imagens.array' => 'O atributo imagens deve ser enviado em formato de lista (array).',
            'imagens.max' => 'Só é possível enviar no máximo 4 imagens por publicação.',

            'imagens.*.image' => 'Cada arquivo enviado deve ser uma imagem.',
            'imagens.*.mimes' => 'As imagens devem estar no formato JPG, JPEG ou PNG.',
        ];
    }
}
