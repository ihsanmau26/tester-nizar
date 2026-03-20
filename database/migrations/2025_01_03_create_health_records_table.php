<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users');
            $table->dateTime('record_date');
            $table->string('diagnosis');
            $table->text('notes')->nullable();
            $table->json('vital_signs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};