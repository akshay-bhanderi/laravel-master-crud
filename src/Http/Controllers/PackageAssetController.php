<?php

namespace AkshayBhanderi\LaravelMasterCrud\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PackageAssetController
{
    private const MIME_TYPES = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'map' => 'application/json',
    ];

    /**
     * Serve a static asset from the package's public/ directory.
     *
     * Only ever reached for paths that don't physically exist in the
     * consuming app's public/ folder (the web server serves those directly
     * without involving Laravel at all). This makes an app-local file at
     * the same path win automatically — no extra config needed to override.
     */
    public function serve(string $path): BinaryFileResponse
    {
        $base = realpath(__DIR__.'/../../../public');
        $full = realpath($base.'/assets/inlancer_portal/'.$path);

        if ($full === false || ! str_starts_with($full, $base) || ! is_file($full)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($full, PATHINFO_EXTENSION));

        return response()->file($full, [
            'Content-Type' => self::MIME_TYPES[$extension] ?? 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
