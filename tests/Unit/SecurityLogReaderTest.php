<?php

namespace Tests\Unit;

use App\Support\SecurityLogReader;
use Tests\TestCase;

class SecurityLogReaderTest extends TestCase
{
    public function test_it_reads_only_normalized_recent_events_and_reports_invalid_lines(): void
    {
        $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'vehicle-security-reader-'.bin2hex(random_bytes(8));

        if (! mkdir($directory)) {
            $this->fail('Unable to create a temporary security log directory.');
        }

        $basePath = $directory.DIRECTORY_SEPARATOR.'security.log';
        config(['logging.channels.security.path' => $basePath]);

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $todayPath = $directory.DIRECTORY_SEPARATOR.'security-'.$today.'.log';
        $yesterdayPath = $directory.DIRECTORY_SEPARATOR.'security-'.$yesterday.'.log';

        file_put_contents($yesterdayPath, $this->entry(date(DATE_ATOM, strtotime('yesterday 23:59')), 'authentication.logout')."\n");
        file_put_contents($todayPath, implode("\n", [
            $this->entry(date(DATE_ATOM, strtotime('today 10:00')), 'authentication.login'),
            'not-json',
            $this->entry(date(DATE_ATOM, strtotime('today 11:00')), 'identity.user_updated'),
        ])."\n");

        try {
            $result = app(SecurityLogReader::class)->latest();

            $this->assertSame(1, $result['malformed_lines']);
            $this->assertCount(3, $result['events']);
            $this->assertSame('identity.user_updated', $result['events'][0]['action']);
            $this->assertSame('authentication.login', $result['events'][1]['action']);
            $this->assertSame('authentication.logout', $result['events'][2]['action']);
            $this->assertSame('192.0.2.10', $result['events'][0]['ip']);
            $this->assertArrayNotHasKey('password', $result['events'][0]);
        } finally {
            foreach (glob($directory.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            rmdir($directory);
        }
    }

    private function entry(string $timestamp, string $action): string
    {
        return json_encode([
            'message' => $action,
            'level_name' => 'INFO',
            'context' => [
                '@timestamp' => $timestamp,
                'event' => ['action' => $action, 'outcome' => 'success'],
                'user' => ['id' => 11],
                'source' => ['ip' => '192.0.2.10'],
                'http' => ['request' => ['method' => 'POST']],
                'url' => ['path' => '/login'],
                'labels' => ['reason' => 'test-only', 'password' => 'not-shown'],
            ],
        ], JSON_THROW_ON_ERROR);
    }
}
