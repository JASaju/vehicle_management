<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function landing(): View
    {
        return view('landing', [
            'canonicalUrl' => $this->publicUrl('/'),
        ]);
    }

    public function robots(): Response
    {
        $sitemapUrl = $this->publicUrl('/sitemap.xml');

        return response(
            "User-agent: *\nAllow: /\nSitemap: {$sitemapUrl}\n",
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }

    public function sitemap(): Response
    {
        $location = htmlspecialchars($this->publicUrl('/'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            .'<url><loc>'.$location.'</loc></url>'
            .'</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    private function publicUrl(string $path): string
    {
        return rtrim((string) config('app.url'), '/').$path;
    }
}
