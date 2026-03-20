<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHealthRecordRequest;
use App\Http\Requests\UpdateHealthRecordRequest;
use App\Http\Resources\HealthRecordResource;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('can:view,record')->only(['show']);
        $this->middleware('can:create,App\Models\HealthRecord')->only(['store']);
        $this->middleware('can:update,record')->only(['update']);
        $this->middleware('can:delete,record')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $patientId = $request->query('patient_id');
        $user = $request->user();

        $cacheKey = "health_records_patient_{$patientId}_user_{$user->id}";

        return Cache::remember($cacheKey, 300, function () use ($patientId, $user) {
            $query = HealthRecord::query()
                ->select(['id', 'patient_id', 'doctor_id', 'record_date', 'diagnosis', 'vital_signs'])
                ->with(['doctor:id,name']);

            if ($patientId) {
                $query->where('patient_id', $patientId);
            }

            if (! $user->isDoctor()) {
                $allowedPatientIds = $user->patients()->pluck('id')->toArray();
                $query->whereIn('patient_id', $allowedPatientIds);
            }

            return HealthRecordResource::collection($query->latest()->paginate(20));
        });
    }

    public function show(HealthRecord $record)
    {
        $cacheKey = "health_record_{$record->id}_user_" . auth()->id();

        return Cache::remember($cacheKey, 300, fn () =>
            new HealthRecordResource($record->load('patient', 'doctor'))
        );
    }

    public function store(StoreHealthRecordRequest $request)
    {
        $record = HealthRecord::create($request->validated());

        Cache::forget("health_records_patient_{$record->patient_id}_user_*");

        return response()->json([
            'message' => 'Record berhasil disimpan',
            'data' => new HealthRecordResource($record)
        ], 201);
    }

    public function update(UpdateHealthRecordRequest $request, HealthRecord $record)
    {
        $record->update($request->validated());

        Cache::forget("health_record_{$record->id}_user_*");
        Cache::forget("health_records_patient_{$record->patient_id}_user_*");

        return new HealthRecordResource($record);
    }

    public function destroy(HealthRecord $record)
    {
        $record->delete();

        Cache::forget("health_record_{$record->id}_user_*");
        Cache::forget("health_records_patient_{$record->patient_id}_user_*");

        return response()->noContent();
    }
}
