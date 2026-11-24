<?php

namespace App\Support;

use Generator;
use RuntimeException;

class SecurityLogReader
{
    private const MAX_LOG_FILES = 30;

    private const MAX_READ_BYTES = 2097152;

    private const MAX_EVENTS = 200;

    /**
     * @return array{events: array<int, array<string, string|null>>, malformed_lines: int}
     */
    public function latest(): array
    {
        $basePath = config('logging.channels.security.path');

        if (! is_string($basePath) || $basePath === '') {
            throw new RuntimeException('The security log path is not configured.');
        }

        $extension = pathinfo($basePath, PATHINFO_EXTENSION);
        $pattern = dirname($basePath).DIRECTORY_SEPARATOR.pathinfo($basePath, PATHINFO_FILENAME)
            .'-*'.($extension === '' ? '' : '.'.$extension);
        $files = glob($pattern);

        if ($files === false) {
            throw new RuntimeException('Unable to list security log files.');
        }

        rsort($files, SORT_STRING);

        $events = [];
        $malformedLines = 0;
        $remainingBytes = self::MAX_READ_BYTES;

        foreach (array_slice($files, 0, self::MAX_LOG_FILES) as $path) {
            if ($remainingBytes <= 0) {
                break;
            }

            if (! is_readable($path)) {
                throw new RuntimeException('A security log file is not readable.');
            }

            $size = filesize($path);

            if ($size === false) {
                throw new RuntimeException('Unable to determine a security log file size.');
            }

            $bytesToRead = min($size, $remainingBytes);

            foreach ($this->linesFromTail($path, $size, $bytesToRead) as $line) {
                $entry = json_decode($line, true);

                if (! is_array($entry)) {
                    $malformedLines++;

                    continue;
                }

                $event = $this->normalize($entry);

                if ($event === null) {
                    $malformedLines++;

                    continue;
                }

                $events[] = $event;
            }

            $remainingBytes -= $bytesToRead;
        }

        usort(
            $events,
            fn (array $first, array $second) => strtotime($second['timestamp']) <=> strtotime($first['timestamp'])
        );

        return [
            'events' => array_slice($events, 0, self::MAX_EVENTS),
            'malformed_lines' => $malformedLines,
        ];
    }

    private function linesFromTail(string $path, int $size, int $bytesToRead): Generator
    {
        if ($bytesToRead === 0) {
            return;
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException('Unable to open a security log file.');
        }

        try {
            $offset = $size - $bytesToRead;

            if (fseek($handle, $offset) !== 0) {
                throw new RuntimeException('Unable to seek within a security log file.');
            }

            if ($offset > 0) {
                fgets($handle);
            }

            while (($line = fgets($handle)) !== false) {
                $line = trim($line);

                if ($line !== '') {
                    yield $line;
                }
            }
        } finally {
            fclose($handle);
        }
    }

    /**
     * @param  array<string, mixed>  $entry
     * @return array<string, string|null>|null
     */
    private function normalize(array $entry): ?array
    {
        $context = is_array($entry['context'] ?? null) ? $entry['context'] : [];
        $event = is_array($context['event'] ?? null) ? $context['event'] : [];
        $user = is_array($context['user'] ?? null) ? $context['user'] : [];
        $source = is_array($context['source'] ?? null) ? $context['source'] : [];
        $http = is_array($context['http'] ?? null) ? $context['http'] : [];
        $request = is_array($http['request'] ?? null) ? $http['request'] : [];
        $url = is_array($context['url'] ?? null) ? $context['url'] : [];
        $labels = is_array($context['labels'] ?? null) ? $context['labels'] : [];
        $timestamp = $this->text($context['@timestamp'] ?? $entry['datetime'] ?? null);

        if ($timestamp === null || strtotime($timestamp) === false) {
            return null;
        }

        return [
            'timestamp' => $timestamp,
            'level' => $this->text($entry['level_name'] ?? null),
            'action' => $this->text($event['action'] ?? $entry['message'] ?? null),
            'outcome' => $this->text($event['outcome'] ?? null),
            'user_id' => $this->text($user['id'] ?? null),
            'ip' => $this->text($source['ip'] ?? null),
            'method' => $this->text($request['method'] ?? null),
            'path' => $this->text($url['path'] ?? null),
            'reason' => $this->text($labels['reason'] ?? null),
        ];
    }

    private function text(mixed $value): ?string
    {
        return is_scalar($value) ? (string) $value : null;
    }
}
