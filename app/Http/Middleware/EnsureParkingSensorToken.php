<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureParkingSensorToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = $request->routeIs('api.parking.plate')
            ? config('parking_sensors.ocr_token')
            : config('parking_sensors.token');
        $providedToken = $request->bearerToken();

        if (
            ! is_string($configuredToken)
            || strlen($configuredToken) < 32
            || count(config('parking_sensors.slots', [])) !== 10
            || strlen((string) config('parking_sensors.token')) < 32
            || strlen((string) config('parking_sensors.ocr_token')) < 32
        ) {
            return response()->json(['message' => 'Parking sensor integration is not configured.'], 503);
        }

        if (! is_string($providedToken) || ! hash_equals($configuredToken, $providedToken)) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
