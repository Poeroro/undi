<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = "User-agent: *\nAllow: /\nSitemap: ".URL::route('sitemap')."\n";

        return response($body, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => now()],
            ['loc' => route('pricing'), 'lastmod' => now()],
            ['loc' => route('templates'), 'lastmod' => now()],
        ])->merge(
            Invitation::query()
                ->where('status', 'active')
                ->latest('updated_at')
                ->get()
                ->map(fn (Invitation $invitation) => [
                    'loc' => $invitation->publicUrl(),
                    'lastmod' => $invitation->updated_at,
                ])
        );

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
