<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('health_record'));
    }

    public function rules(): array
    {
        return [
            'record_date' => ['sometimes', 'required', 'date'],
            'diagnosis'   => ['sometimes', 'required', 'string', 'max:255'],
            'notes'       => ['nullable', 'string'],
            'vital_signs' => ['sometimes', 'required', 'array'],
        ];
    }
}
