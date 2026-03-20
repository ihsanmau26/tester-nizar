<?php

namespace App\Policies;

use App\Models\HealthRecord;
use App\Models\User;

class HealthRecordPolicy
{
    public function view(User $user, HealthRecord $record): bool
    {
        return $user->id === $record->doctor_id ||
               $user->patients()->where('id', $record->patient_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isDoctor();
    }

    public function update(User $user, HealthRecord $record): bool
    {
        return $user->id === $record->doctor_id;
    }

    public function delete(User $user, HealthRecord $record): bool
    {
        return $user->id === $record->doctor_id;
    }
}
