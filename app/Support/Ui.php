<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Small HTML helpers shared by the PowerGrid tables and Blade views.
 * Every user-supplied string is escaped with e() before it is returned.
 */
class Ui
{
    private const STATUS_COLORS = [
        'active' => 'green',
        'completed' => 'green',
        'in_maintenance' => 'yellow',
        'in_progress' => 'yellow',
        'scheduled' => 'blue',
        'inactive' => 'gray',
        'sold' => 'red',
    ];

    private const COLOR_CLASSES = [
        'green' => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'blue' => 'bg-blue-100 text-blue-800',
        'gray' => 'bg-gray-100 text-gray-700',
        'red' => 'bg-red-100 text-red-800',
    ];

    public static function badge(string $text, string $color = 'gray'): HtmlString
    {
        $classes = self::COLOR_CLASSES[$color] ?? self::COLOR_CLASSES['gray'];

        return new HtmlString(
            '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium '
                .$classes.'">'.e($text).'</span>'
        );
    }

    public static function status(string $value): HtmlString
    {
        return self::badge(Str::headline($value), self::STATUS_COLORS[$value] ?? 'gray');
    }

    /** Date cell: red when expired, yellow when it expires within 30 days. */
    public static function expiry(?CarbonInterface $date): HtmlString
    {
        if (! $date) {
            return new HtmlString('<span class="text-gray-400">—</span>');
        }

        $label = $date->format('d M Y');

        if ($date->copy()->endOfDay()->isPast()) {
            return self::badge($label.' (expired)', 'red');
        }

        if ($date->lte(now()->addDays(30))) {
            return self::badge($label, 'yellow');
        }

        return new HtmlString(e($label));
    }

    public static function date(?CarbonInterface $date): HtmlString
    {
        return new HtmlString($date ? e($date->format('d M Y')) : '<span class="text-gray-400">—</span>');
    }

    public static function money(float|int|string|null $value): string
    {
        return number_format((float) $value, 2);
    }

    /** ['active', 'in_maintenance'] => ['active' => 'Active', 'in_maintenance' => 'In Maintenance'] */
    public static function options(array $values): array
    {
        return collect($values)->mapWithKeys(fn ($v) => [$v => Str::headline($v)])->all();
    }

    /** Same list in the shape PowerGrid's Filter::select()->dataSource() expects. */
    public static function filterOptions(array $values): array
    {
        return collect($values)
            ->map(fn ($v) => ['value' => $v, 'label' => Str::headline($v)])
            ->values()
            ->all();
    }

    /** CSS class for PDF cells: red = expired, yellow = expires within 30 days. */
    public static function expiryClass(?CarbonInterface $date): string
    {
        if (! $date) {
            return '';
        }

        if ($date->copy()->endOfDay()->isPast()) {
            return 'red';
        }

        return $date->lte(now()->addDays(30)) ? 'yellow' : '';
    }

    /** "2d 3h 15m" between two moments ($to defaults to now). */
    public static function duration(CarbonInterface $from, ?CarbonInterface $to = null): string
    {
        $seconds = max(0, ($to ?? now())->getTimestamp() - $from->getTimestamp());

        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        $parts = [];

        if ($days) {
            $parts[] = $days.'d';
        }

        if ($days || $hours) {
            $parts[] = $hours.'h';
        }

        $parts[] = $minutes.'m';

        return implode(' ', $parts);
    }

    /**
     * Edit link + Delete button. wire:click runs deleteRow() on the PowerGrid component.
     * With $canDelete = false the Delete button is replaced by a muted "Has history" note.
     * $extra adds more links, e.g. [['PDF', $url]], opened in a new tab.
     */
    public static function rowActions(string $editUrl, int $id, string $confirm = 'Delete this record?', array $extra = [], bool $canDelete = true): HtmlString
    {
        $extraLinks = '';
        foreach ($extra as [$label, $url]) {
            $extraLinks .= '<a href="'.e($url).'" target="_blank" rel="noopener noreferrer" class="rounded bg-gray-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-gray-700">'.e($label).'</a>';
        }

        return new HtmlString(
            '<div class="flex items-center gap-2">'
                .$extraLinks
                .'<a href="'.e($editUrl).'" class="rounded bg-indigo-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-indigo-700">Edit</a>'
                .($canDelete
                    ? '<button type="button" wire:click="deleteRow('.$id.')" wire:confirm="'.e($confirm).'" '
                        .'class="rounded bg-red-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-red-700">Delete</button>'
                    : '<span class="text-xs text-gray-400">Has history</span>')
                .'</div>'
        );
    }
}
