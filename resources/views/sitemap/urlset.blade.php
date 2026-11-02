{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
{!! '<?xml-stylesheet type="text/xsl" href="'.asset('sitemap.xsl').'"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
@if(!empty($url['lastmod']))
        <lastmod>{{ $url['lastmod'] }}</lastmod>
@endif
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
@if(!empty($url['image']))
        <image:image>
            <image:loc>{{ $url['image'] }}</image:loc>
            <image:title>{{ $url['image_title'] }}</image:title>
        </image:image>
@endif
    </url>
@endforeach
</urlset>
