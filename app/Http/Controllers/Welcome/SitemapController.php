<?php

namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    // minutes the generated XML is cached for
    const CACHE_MINUTES = 60;

    // Post::type 0 page slugs that should not be indexed
    const EXCLUDED_PAGES = ['home'];

    public function index()
    {
        $xml = Cache::remember('sitemap:index', now()->addMinutes(self::CACHE_MINUTES), function () {
            $sitemaps = [
                ['loc' => route('sitemap.pages'), 'lastmod' => $this->lastmod(Post::where('type', 0))],
                ['loc' => route('sitemap.categories'), 'lastmod' => $this->lastmod(Attribute::whereIn('type', [0, 6]))],
                ['loc' => route('sitemap.products'), 'lastmod' => $this->lastmod(Post::where('type', 2))],
                ['loc' => route('sitemap.blog'), 'lastmod' => $this->lastmod(Post::where('type', 1))],
            ];

            return view('sitemap.index', compact('sitemaps'))->render();
        });

        return $this->xml($xml);
    }

    public function pages()
    {
        $xml = Cache::remember('sitemap:pages', now()->addMinutes(self::CACHE_MINUTES), function () {
            $urls = [[
                'loc' => route('index'),
                'lastmod' => $this->lastmod(Post::where('type', 2)->where('status', 'active')),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ]];

            $pages = Post::where('type', 0)->where('status', 'active')
                ->whereNotIn('slug', self::EXCLUDED_PAGES)
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->get(['slug', 'updated_at']);

            foreach ($pages as $page) {
                $urls[] = [
                    'loc' => route('pageView', $page->slug),
                    'lastmod' => optional($page->updated_at)->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.5',
                ];
            }

            return view('sitemap.urlset', compact('urls'))->render();
        });

        return $this->xml($xml);
    }

    public function categories()
    {
        $xml = Cache::remember('sitemap:categories', now()->addMinutes(self::CACHE_MINUTES), function () {
            $urls = [];

            $productCategories = Attribute::where('type', 0)->where('status', 'active')
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->get(['slug', 'updated_at']);

            foreach ($productCategories as $category) {
                $urls[] = [
                    'loc' => route('productCategory', $category->slug),
                    'lastmod' => optional($category->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }

            $blogCategories = Attribute::where('type', 6)->where('status', 'active')
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->get(['slug', 'updated_at']);

            foreach ($blogCategories as $category) {
                $urls[] = [
                    'loc' => route('blogCategory', $category->slug),
                    'lastmod' => optional($category->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
            }

            return view('sitemap.urlset', compact('urls'))->render();
        });

        return $this->xml($xml);
    }

    public function products()
    {
        $xml = Cache::remember('sitemap:products', now()->addMinutes(self::CACHE_MINUTES), function () {
            $urls = [];

            Post::with('imageFile')
                ->where('type', 2)->where('status', 'active')
                ->whereDate('created_at', '<=', now())
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->orderBy('id', 'desc')
                ->chunk(500, function ($products) use (&$urls) {
                    foreach ($products as $product) {
                        $urls[] = [
                            'loc' => route('productView', $product->slug),
                            'lastmod' => optional($product->updated_at)->toAtomString(),
                            'changefreq' => 'weekly',
                            'priority' => '0.9',
                            'image' => $product->imageFile ? asset($product->image()) : null,
                            'image_title' => $product->name,
                        ];
                    }
                });

            return view('sitemap.urlset', compact('urls'))->render();
        });

        return $this->xml($xml);
    }

    public function blog()
    {
        $xml = Cache::remember('sitemap:blog', now()->addMinutes(self::CACHE_MINUTES), function () {
            $urls = [];

            Post::with('imageFile')
                ->where('type', 1)->where('status', 'active')
                ->whereDate('created_at', '<=', now())
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->orderBy('id', 'desc')
                ->chunk(500, function ($posts) use (&$urls) {
                    foreach ($posts as $post) {
                        $urls[] = [
                            'loc' => route('blogView', $post->slug),
                            'lastmod' => optional($post->updated_at)->toAtomString(),
                            'changefreq' => 'monthly',
                            'priority' => '0.6',
                            'image' => $post->imageFile ? asset($post->image()) : null,
                            'image_title' => $post->name,
                        ];
                    }
                });

            return view('sitemap.urlset', compact('urls'))->render();
        });

        return $this->xml($xml);
    }

    private function lastmod($query)
    {
        $date = $query->max('updated_at');

        return $date ? \Carbon\Carbon::parse($date)->toAtomString() : now()->toAtomString();
    }

    private function xml($content)
    {
        return response($content, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
