<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect([
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => route('shop'), 'priority' => '0.9'],
            ['loc' => route('blog.index'), 'priority' => '0.7'],
        ]);

        Product::active()->select('slug', 'updated_at')->each(function ($p) use ($urls) {
            $urls->push(['loc' => route('products.show', $p), 'lastmod' => $p->updated_at?->toAtomString(), 'priority' => '0.8']);
        });

        Category::active()->select('slug', 'updated_at')->each(function ($c) use ($urls) {
            $urls->push(['loc' => route('shop', ['category' => $c->slug]), 'priority' => '0.7']);
        });

        Blog::where('is_published', true)->select('slug', 'updated_at')->each(function ($b) use ($urls) {
            $urls->push(['loc' => route('blog.show', $b), 'priority' => '0.6']);
        });

        return response()->view('sitemap.index', compact('urls'))->header('Content-Type', 'application/xml');
    }
}
