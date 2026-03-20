<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureDoctorOrOwner
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if ($user->isDoctor()) {
            return $next($request);
        }

        $resource = $request->route('patient') ?: $request->route('health_record');

        if ($resource && method_exists($resource, 'user_id') && $resource->user_id === $user->id) {
            return $next($request);
        }

        if ($resource && method_exists($resource, 'patient_id') && $resource->patient_id) {
            return $next($request);
        }

        abort(403);
    }
}
