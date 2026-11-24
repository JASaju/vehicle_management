<?php

namespace App\Http\Middleware;

use App\Support\SecurityEventLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            app(SecurityEventLogger::class)->record('authorization.denied', 'failure', $request->user(), [
                'reason' => 'admin_required',
                'route_name' => $request->route()?->getName(),
            ]);

            abort(403, 'Administrators only.');
        }

        return $next($request);
    }
}
