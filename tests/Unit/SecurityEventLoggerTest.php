<?php

namespace Tests\Unit;

use App\Models\User;
use App\Support\SecurityEventLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\JsonFormatter;
use Tests\TestCase;

class SecurityEventLoggerTest extends TestCase
{
    public function test_security_events_are_json_and_exclude_sensitive_inputs(): void
    {
        $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'vehicle-security-'.bin2hex(random_bytes(8));
        if (! mkdir($directory)) {
            $this->fail('Unable to create a temporary security log directory.');
        }

        $channel = config('logging.channels.security');
        $channel['path'] = $directory.DIRECTORY_SEPARATOR.'security.log';
        config(['logging.channels.security' => $channel]);
        Log::forgetChannel('security');

        config([
            'logging.channels.security.formatter' => JsonFormatter::class,
        ]);
        $request = Request::create(
            '/login?token=query-secret',
            'POST',
            server: ['REMOTE_ADDR' => '192.0.2.25']
        );
        $this->app->instance('request', $request);

        $actor = (new User)->forceFill(['id' => 17]);
        app(SecurityEventLogger::class)->record(
            'authentication.login',
            'failure',
            $actor,
            [
                'reason' => 'invalid_credentials',
                'password' => 'password-secret',
                'token' => 'token-secret',
                'unapproved' => 'discarded',
            ]
        );

        try {
            $files = glob($directory.DIRECTORY_SEPARATOR.'security-*.log');
            $this->assertIsArray($files);
            $this->assertCount(1, $files);

            $line = file_get_contents($files[0]);
            $this->assertIsString($line);
            $entry = json_decode($line, true, flags: JSON_THROW_ON_ERROR);

            $this->assertSame('authentication.login', $entry['message']);
            $this->assertSame('authentication.login', $entry['context']['event']['action']);
            $this->assertSame('failure', $entry['context']['event']['outcome']);
            $this->assertSame(17, $entry['context']['user']['id']);
            $this->assertSame('192.0.2.25', $entry['context']['source']['ip']);
            $this->assertSame(['reason' => 'invalid_credentials'], $entry['context']['labels']);
            $this->assertStringNotContainsString('query-secret', $line);
            $this->assertStringNotContainsString('password-secret', $line);
            $this->assertStringNotContainsString('token-secret', $line);
            $this->assertStringNotContainsString('discarded', $line);
        } finally {
            Log::forgetChannel('security');

            foreach (glob($directory.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            rmdir($directory);
        }
    }
}
