<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Shared\PublicacaoRules;

class PublicacaoRequest extends FormRequest
{
    use PublicacaoRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return $this->publicacaoRules();
    }

    public function messages(): array
    {
        return $this->publicacaoMessages();
    }
}
