<?php

namespace App\Domains\Projects\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'field' => ['nullable', Rule::in(['title', 'applicant', 'trl', 'province'])],
            'per_page' => ['nullable', 'integer', Rule::in([10, 25, 50])],
        ];
    }

    /**
     * @return array{search: string|null, field: string, per_page: int}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'search' => $validated['search'] ?? null,
            'field' => $validated['field'] ?? 'title',
            'per_page' => (int) ($validated['per_page'] ?? 25),
        ];
    }
}
