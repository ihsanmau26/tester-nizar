<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function view(User $user, Patient $patient): bool
    {
        return $user->id === $patient->user_id || $user->isDoctor();
    }

    public function update(User $user, Patient $patient): bool
    {
        return $user->id === $patient->user_id || $user->isDoctor();
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->isDoctor();
    }
}
