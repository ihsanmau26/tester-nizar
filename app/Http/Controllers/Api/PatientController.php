<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('can:view,patient')->only(['show']);
        $this->middleware('can:update,patient')->only(['update']);
        $this->middleware('can:delete,patient')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Patient::query();

        if (! $user->isDoctor()) {
            $query->where('user_id', $user->id);
        }

        return PatientResource::collection($query->latest()->paginate(20));
    }

    public function show(Patient $patient)
    {
        return new PatientResource($patient);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:10'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'array'],
        ]);

        $patient = Patient::create(array_merge($data, ['user_id' => $request->user()->id]));

        return response()->json(new PatientResource($patient), 201);
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'date_of_birth' => ['sometimes', 'required', 'date'],
            'gender' => ['sometimes', 'required', 'string', 'max:10'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'array'],
        ]);

        $patient->update($data);

        return new PatientResource($patient);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return response()->noContent();
    }
}
