<?php

namespace App\Http\Requests\Filter;

use Illuminate\Foundation\Http\FormRequest;

class ArtigoUniversitarioFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'    => ['nullable', 'string'],
            'hashtags'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.string'   => 'O título deve ser uma string.',
            'hashtags.string' => 'As hashtags devem estar em formato de texto separado por espaço.',
        ];
    }
}
