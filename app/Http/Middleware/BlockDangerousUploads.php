<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * Rejects any request that carries an uploaded file which could be executed
 * on the server (e.g. shell.php, image.php.jpg, a PNG with PHP code inside).
 *
 * Controllers save files with the client's original extension into public/,
 * so this guard runs globally before any controller sees the upload.
 */
class BlockDangerousUploads
{
    // Only these extensions may be uploaded anywhere in the site.
    protected $allowed = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'bmp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt',
        'zip', 'rar',
        'mp4', 'webm', 'mov', 'wmv', 'mp3', 'wav', 'ogg',
    ];

    // Extensions that may never appear anywhere in a file name (catches "x.php.jpg").
    protected $dangerousPattern = '/\.(php\d?|phtml|phar|pht|phps|pgif|inc|shtml?|cgi|pl|py|sh|bash|asp|aspx|jsp|htaccess|htpasswd|user\.ini|exe|bat)(\.|$)/i';

    // File types whose content is scanned for embedded PHP / script code.
    protected $scanContent = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'bmp', 'pdf', 'txt', 'csv'];

    public function handle(Request $request, Closure $next)
    {
        foreach ($this->flatten($request->allFiles()) as $file) {
            if (!$this->isSafe($file)) {
                $message = 'এই ফাইলটি আপলোড করা যাবে না। শুধুমাত্র ছবি/ডকুমেন্ট ফাইল আপলোড করুন।';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->withInput($request->except(array_keys($request->allFiles())))
                    ->withErrors(['file' => $message])
                    ->with('error', $message);
            }
        }

        return $next($request);
    }

    protected function isSafe($file)
    {
        if (!$file instanceof UploadedFile) {
            return true;
        }
        if (!$file->isValid()) {
            return true; // Laravel's own validation handles failed uploads
        }

        $name = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === '' || !in_array($ext, $this->allowed, true)) {
            return false;
        }
        if (preg_match($this->dangerousPattern, $name) || strpos($name, "\0") !== false) {
            return false;
        }

        if (in_array($ext, $this->scanContent, true)) {
            $content = @file_get_contents($file->getRealPath());
            if ($content === false) {
                return false;
            }
            // Long markers only for binary files, so random image bytes never match
            if (preg_match('/<\?php|<script[\s>]|__halt_compiler|base64_decode\s*\(/i', $content)) {
                return false;
            }
            // Text formats (SVG can carry JavaScript) get a stricter scan
            if (in_array($ext, ['svg', 'txt', 'csv'], true)
                && preg_match('/<\?=|<%|javascript:|\bon[a-z]+\s*=|<iframe|<embed|<object|<foreignobject/i', $content)) {
                return false;
            }
        }

        return true;
    }

    protected function flatten(array $files)
    {
        $out = [];
        array_walk_recursive($files, function ($file) use (&$out) {
            $out[] = $file;
        });
        return $out;
    }
}
