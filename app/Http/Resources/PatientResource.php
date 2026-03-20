<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
            'gender' => $this->gender,
            'blood_type' => $this->blood_type,
            'allergies' => $this->allergies,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
