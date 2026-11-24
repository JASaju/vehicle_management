<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\SecurityEventLogger;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class SecurityLogPageTest extends TestCase
{
    public function test_security_log_page_is_admin_only(): void
    {
        $user = new User;
        $user->forceFill([
            'id' => 2,
            'name' => 'Test Staff',
            'email' => 'staff@example.test',
            'role' => 'staff',
            'is_active' => true,
        ]);
        $events = Mockery::mock(SecurityEventLogger::class);
        $events->shouldReceive('record')
            ->once()
            ->with(
                'authorization.denied',
                'failure',
                Mockery::type(User::class),
                ['reason' => 'admin_required', 'route_name' => 'security-logs.index']
            );
        $this->app->instance(SecurityEventLogger::class, $events);

        $this->actingAs($user)
            ->get(route('security-logs.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_an_empty_security_log_page(): void
    {
        $user = new User;
        $user->forceFill([
            'id' => 1,
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'role' => 'admin',
            'is_active' => true,
        ]);
        config([
            'logging.channels.security.path' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'missing-security-'.Str::uuid().DIRECTORY_SEPARATOR.'security.log',
        ]);
        $this->withoutVite();

        $this->actingAs($user)
            ->get(route('security-logs.index'))
            ->assertOk()
            ->assertSee('Security Logs')
            ->assertSee('No security events have been recorded yet.');
    }
}
