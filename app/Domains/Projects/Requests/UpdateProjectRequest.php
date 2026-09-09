<?php

namespace App\Domains\Projects\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'objective' => ['nullable', 'string'],
            'innovation_value' => ['nullable', 'string'],
            'trl_level_id' => ['nullable', 'integer', 'exists:trl_levels,id'],
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
            'located_entity' => ['nullable', 'string', 'max:255'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'EBCT_amount' => ['nullable', 'string', 'max:255'],
            'countries' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'trl_level_id.exists' => 'El nivel TRL seleccionado no existe.',
            'area_id.exists' => 'La vertical seleccionada no existe.',
            'province_id.exists' => 'La provincia seleccionada no existe.',
        ];
    }
}
