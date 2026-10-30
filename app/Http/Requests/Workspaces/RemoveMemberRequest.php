<?php

namespace App\Http\Requests\Workspaces;

use App\Models\Workspace;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RemoveMemberRequest extends FormRequest
{
    /**
     * Only the workspace creator can remove members.
     */
    public function authorize(): bool
    {
        $workspace = Workspace::where('uuid', $this->route('workspaceUuid'))->first();

        return $workspace !== null && $workspace->created_by === $this->user()?->getKey();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'userUuid' => $this->route('userUuid'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userUuid' => 'required|uuid',
        ];
    }

    public function messages(): array
    {
        return [
            'userUuid.required' => 'O membro é obrigatório.',
            'userUuid.uuid' => 'Identificador de membro inválido.',
        ];
    }
}
