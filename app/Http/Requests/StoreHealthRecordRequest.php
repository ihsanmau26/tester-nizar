<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isDoctor() === true;
    }

    public function rules(): array
    {
        return [
            'patient_id'  => ['required', 'exists:patients,id'],
            'record_date' => ['required', 'date'],
            'diagnosis'   => ['required', 'string', 'max:255'],
            'notes'       => ['nullable', 'string'],
            'vital_signs' => ['required', 'array'],
        ];
    }
}
