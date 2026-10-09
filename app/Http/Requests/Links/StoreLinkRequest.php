<?php

namespace App\Http\Requests\Links;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:2048'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'url.required' => 'A URL é obrigatória.',
            'url.url' => 'A URL informada não é válida.',
            'url.max' => 'A URL não pode ter mais de 2048 caracteres.',
            'category_id.integer' => 'A categoria deve ser um número inteiro.',
            'category_id.exists' => 'A categoria informada não existe.',
        ];
    }
}
