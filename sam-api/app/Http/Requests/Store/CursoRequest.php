<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class CursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_instituicao' => ['required', 'integer'],
            'nome_curso'     => ['required', 'string', 'max:150'],
            'situacao'       => ['string', 'in:A,I'],
            'duracao_minima' => ['required', 'integer', 'min:1'],
            'duracao_maxima' => ['required', 'integer', 'min:1', 'gte:duracao_minima']
        ];
    }    

    public function messages(): array
    {
        return [
            'id_instituicao.required' => 'O atributo id_instituicao é obrigatório.',
            'id_instituicao.integer'  => 'O atributo id_instituicao deve ser um número inteiro',
    
            'nome_curso.required' => 'O atributo nome_curso é obrigatório.',
            'nome_curso.string'   => 'O atributo nome_curso deve ser um texto.',
            'nome_curso.max'      => 'O atributo nome_curso deve ter no máximo 150 caracteres.',
    
            'situacao.string'   => 'O atributo situacao deve ser um texto.',
            'situacao.in'       => 'O atributo situacao deve ser "A" (Ativo) ou "I" (Inativo).',
    
            'duracao_minima.required' => 'O atributo duracao_minima (em anos) é obrigatória.',
            'duracao_minima.integer'  => 'O atributo duracao_minima (em anos) deve ser um número inteiro.',
            'duracao_minima.min'      => 'O atributo duracao_minima (em anos) deve ser no mínimo 1 ano.',
    
            'duracao_maxima.required' => 'O atributo duracao_maxima (em anos) é obrigatória.',
            'duracao_maxima.integer'  => 'O atributo duracao_maxima (em anos) deve ser um número inteiro.',
            'duracao_maxima.min'      => 'O atributo duracao_maxima (em anos) deve ser no mínimo 1 ano.',
            'duracao_maxima.gte'      => 'O atributo duracao_maxima (em anos) deve ser maior ou igual à duração mínima.',
        ];
    }    
}
