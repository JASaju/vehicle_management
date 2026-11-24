<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicSeoTest extends TestCase
{
    public function test_landing_page_is_indexable_and_uses_the_configured_canonical_origin(): void
    {
        config(['app.url' => 'https://fleet.example.test']);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Vehicle Manager | Fleet and Vehicle Management</title>', false)
            ->assertSee('name="robots" content="index, follow"', false)
            ->assertSee('rel="canonical" href="https://fleet.example.test/"', false)
            ->assertSee('property="og:url" content="https://fleet.example.test/"', false)
            ->assertSee('name="twitter:title" content="Vehicle Manager | Fleet and Vehicle Management"', false)
            ->assertSee('Manage vehicles, drivers, maintenance, fuel, parking, and reports', false);
    }

    public function test_sitemap_and_robots_only_advertise_the_public_landing_page(): void
    {
        config(['app.url' => 'https://fleet.example.test']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>https://fleet.example.test/</loc>', false)
            ->assertDontSee('/dashboard');

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: https://fleet.example.test/sitemap.xml', false);
    }

    public function test_login_and_dashboard_are_not_indexable_and_dashboard_stays_authenticated(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('name="robots" content="noindex, nofollow"', false)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/dashboard')
            ->assertRedirect(route('login'))
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_public_and_private_responses_receive_baseline_security_headers(): void
    {
        $this->get('/')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }
}
