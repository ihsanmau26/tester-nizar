<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthRecordResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'doctor' => $this->whenLoaded('doctor', fn () => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
            ]),
            'record_date' => $this->record_date->format('Y-m-d H:i'),
            'diagnosis' => $this->diagnosis,
            'notes' => $this->notes,
            'vital_signs' => $this->vital_signs,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
