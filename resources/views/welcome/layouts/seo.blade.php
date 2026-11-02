{{--
    Site-wide SEO defaults. Pages add their own title/description/og tags in
    @section('SEO'); this fills in what every page needs:
    canonical, robots for private pages, social defaults and site JSON-LD.
--}}
@php
    $seoPrivate = request()->is(
        'customer', 'customer/*', 'carts', 'checkout', 'checkout-*', 'login', 'register',
        'forgot-password', 'reset-password/*', 'order-success/*', 'order-payment/*',
        'order-invoice/*', 'OrderTrack', 'my-wishlist', 'my-compare', 'search', 'product-search'
    );
    // canonical keeps only the page number, so filter/tracking params don't create duplicates
    $seoPage = (int) request()->query('page', 1);
    $seoCanonical = url()->current() . ($seoPage > 1 ? '?page=' . $seoPage : '');
@endphp
@if($seoPrivate)
<meta name="robots" content="noindex, follow" />
@else
<meta name="robots" content="index, follow, max-image-preview:large" />
@endif
@hasSection('canonical')
@yield('canonical')
@else
<link rel="canonical" href="{{ $seoCanonical }}" />
@endif
<meta property="og:site_name" content="{{ general()->title }}" />
<meta property="og:locale" content="bn_BD" />
@hasSection('ogType')
<meta property="og:type" content="@yield('ogType')" />
@else
<meta property="og:type" content="website" />
@endif
<meta name="twitter:card" content="summary_large_image" />
@if(request()->routeIs('index'))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        array_filter([
            '@type' => 'Organization',
            '@id' => route('index').'#organization',
            'name' => general()->title,
            'url' => route('index'),
            'logo' => asset(general()->logo()),
            'email' => general()->email ?: null,
            'telephone' => general()->mobile ?: null,
            'sameAs' => array_values(array_filter([general()->facebook_link ?? null, general()->instagram_link ?? null, general()->youtube_link ?? null, general()->twitter_link ?? null, general()->linkedin_link ?? null])),
        ]),
        [
            '@type' => 'WebSite',
            '@id' => route('index').'#website',
            'name' => general()->title,
            'url' => route('index'),
            'publisher' => ['@id' => route('index').'#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('productSearch').'?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
