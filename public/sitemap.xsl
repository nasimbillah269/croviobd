<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    exclude-result-prefixes="s image">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html>
<head>
    <title>XML Sitemap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="robots" content="noindex,follow"/>
    <style>
        body { font-family: -apple-system, "Segoe UI", Roboto, sans-serif; color: #1f2937; background: #f5f6f8; margin: 0; padding: 32px 16px; }
        .wrap { max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        p { color: #6b7280; font-size: 14px; margin: 0 0 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { background: #f9fafb; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #6b7280; padding: 12px 16px; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
        td { padding: 10px 16px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tr:last-child td { border-bottom: 0; }
        tr:hover td { background: #fafafa; }
        a { color: #ff005e; text-decoration: none; word-break: break-all; }
        a:hover { text-decoration: underline; }
        img { width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; display: block; }
        .muted { color: #6b7280; white-space: nowrap; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>XML Sitemap</h1>
    <xsl:choose>
        <xsl:when test="s:sitemapindex">
            <p>This index contains <xsl:value-of select="count(s:sitemapindex/s:sitemap)"/> sitemaps.</p>
            <div class="card">
                <table>
                    <tr><th>Sitemap</th><th>Last Modified</th></tr>
                    <xsl:for-each select="s:sitemapindex/s:sitemap">
                        <tr>
                            <td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                            <td class="muted"><xsl:value-of select="concat(substring(s:lastmod,1,10),' ',substring(s:lastmod,12,5))"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </div>
        </xsl:when>
        <xsl:otherwise>
            <p>This sitemap contains <xsl:value-of select="count(s:urlset/s:url)"/> URLs.</p>
            <div class="card">
                <table>
                    <tr><th></th><th>URL</th><th>Priority</th><th>Change Freq.</th><th>Last Modified</th></tr>
                    <xsl:for-each select="s:urlset/s:url">
                        <tr>
                            <td style="width:40px;"><xsl:if test="image:image"><img src="{image:image/image:loc}" alt=""/></xsl:if></td>
                            <td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                            <td class="muted"><xsl:value-of select="s:priority"/></td>
                            <td class="muted"><xsl:value-of select="s:changefreq"/></td>
                            <td class="muted"><xsl:value-of select="concat(substring(s:lastmod,1,10),' ',substring(s:lastmod,12,5))"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </div>
        </xsl:otherwise>
    </xsl:choose>
</div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
