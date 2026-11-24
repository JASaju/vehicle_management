<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class SecurityEventLogger
{
    private const SAFE_LABELS = [
        'assigned_role',
        'new_active',
        'new_role',
        'password_changed',
        'previous_active',
        'previous_role',
        'reason',
        'route_name',
        'target_user_id',
    ];

    public function record(string $action, string $outcome, ?User $actor = null, array $labels = []): void
    {
        $request = request();
        $category = str_starts_with($action, 'authentication.') ? 'authentication' : 'iam';
        $safeLabels = $this->safeLabels($labels);

        $context = [
            '@timestamp' => now()->toIso8601String(),
            'event' => [
                'kind' => 'event',
                'category' => [$category],
                'type' => ['info'],
                'action' => $action,
                'outcome' => $outcome,
                'dataset' => 'vehicle_app.security',
            ],
            'service' => [
                'name' => config('app.name'),
                'environment' => config('app.env'),
            ],
            'user' => ['id' => $actor?->getAuthIdentifier()],
            'source' => ['ip' => $request->ip()],
            'http' => ['request' => ['method' => $request->method()]],
            'url' => ['path' => '/'.$request->path()],
        ];

        if ($safeLabels !== []) {
            $context['labels'] = $safeLabels;
        }

        Log::channel('security')->log(
            $outcome === 'failure' ? 'warning' : 'info',
            $action,
            $context
        );
    }

    private function safeLabels(array $labels): array
    {
        return collect($labels)
            ->only(self::SAFE_LABELS)
            ->filter(fn ($value) => is_string($value) || is_int($value) || is_bool($value))
            ->all();
    }
}
