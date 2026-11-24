<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureActiveUser;
use App\Models\User;
use App\Support\SecurityEventLogger;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class EnsureActiveUserTest extends TestCase
{
    public function test_inactive_web_user_is_logged_out_and_redirected_to_login(): void
    {
        Auth::shouldReceive('logout')->once();
        $this->mockInactiveAccountEvent();

        $request = Request::create('/dashboard', 'GET');
        $request->setUserResolver(fn () => new User(['is_active' => false]));

        $session = new Store('test_session', new ArraySessionHandler(120));
        $session->start();
        $session->put('private_value', 'value');
        $request->setLaravelSession($session);

        $response = (new EnsureActiveUser)->handle($request, fn () => response('protected'));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('login'), $response->headers->get('Location'));
        $this->assertNull($session->get('private_value'));
    }

    public function test_inactive_json_user_gets_unauthenticated_response(): void
    {
        Auth::shouldReceive('logout')->once();
        $this->mockInactiveAccountEvent();

        $request = Request::create('/api/user', 'GET', server: ['HTTP_ACCEPT' => 'application/json']);
        $request->setUserResolver(fn () => new User(['is_active' => false]));

        $response = (new EnsureActiveUser)->handle($request, fn () => response('protected'));

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame(['message' => 'Unauthenticated.'], json_decode($response->getContent(), true));
    }

    private function mockInactiveAccountEvent(): void
    {
        $events = Mockery::mock(SecurityEventLogger::class);
        $events->shouldReceive('record')
            ->once()
            ->with(
                'authorization.denied',
                'failure',
                Mockery::type(User::class),
                ['reason' => 'inactive_account', 'route_name' => null]
            );

        $this->app->instance(SecurityEventLogger::class, $events);
    }
}
