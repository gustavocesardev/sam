<?php

namespace App\Http\Requests;

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
            'id_instituicao' => ['required', 'exists:instituicao,id'],
            'nome_curso' => ['required', 'string', 'max:150'],
            'situacao' => ['string', 'in:A,I'],
            'duracao_minima' => ['required', 'integer', 'min:1'],
            'duracao_maxima' => ['required', 'integer', 'min:1', 'gte:duracao_minima']
        ];
    }    

    public function messages(): array
    {
        return [
            'id_instituicao.required' => 'O ID da instituição é obrigatório.',
            'id_instituicao.exists'   => 'A instituição informada não existe.',
    
            'nome_curso.required' => 'O nome do curso é obrigatório.',
            'nome_curso.string'   => 'O nome do curso deve ser um texto.',
            'nome_curso.max'      => 'O nome do curso deve ter no máximo 150 caracteres.',
    
            'situacao.string'   => 'A situação do curso deve ser um texto.',
            'situacao.in'       => 'A situação do curso deve ser "A" (Ativo) ou "I" (Inativo).',
    
            'duracao_minima.required' => 'A duração mínima do curso (em anos) é obrigatória.',
            'duracao_minima.integer'  => 'A duração mínima do curso (em anos) deve ser um número inteiro.',
            'duracao_minima.min'      => 'A duração mínima do curso (em anos) deve ser no mínimo 1 ano.',
    
            'duracao_maxima.required' => 'A duração máxima do curso (em anos) é obrigatória.',
            'duracao_maxima.integer'  => 'A duração máxima do curso (em anos) deve ser um número inteiro.',
            'duracao_maxima.min'      => 'A duração máxima do curso (em anos) deve ser no mínimo 1 ano.',
            'duracao_maxima.gte'      => 'A duração máxima (em anos) deve ser maior ou igual à duração mínima.',
        ];
    }    
}
