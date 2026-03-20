<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'record_date',
        'diagnosis',
        'notes',
        'vital_signs',
    ];

    protected $casts = [
        'record_date' => 'datetime',
        'vital_signs' => 'array',
        'notes' => 'encrypted',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
