<?php

namespace Tests\Unit;

use App\Support\Ui;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

class UiTest extends TestCase
{
    public function test_new_tab_report_actions_disable_the_opener_and_escape_the_url(): void
    {
        $actions = Ui::rowActions(
            '/vehicles/1/edit',
            1,
            extra: [['PDF', 'https://reports.example.test/?search="vehicle"']]
        );

        $this->assertStringContainsString('target="_blank" rel="noopener noreferrer"', $actions);
        $this->assertStringContainsString('search=&quot;vehicle&quot;', $actions);
        $this->assertStringNotContainsString('search="vehicle"', $actions);
    }

    public function test_badge_returns_safe_html_for_powergrid_fields(): void
    {
        $badge = Ui::badge('<script>alert(1)</script>', 'green');

        $this->assertInstanceOf(HtmlString::class, $badge);
        $this->assertStringContainsString('&lt;script&gt;', $badge);
        $this->assertStringNotContainsString('<script>', $badge);
    }
}
