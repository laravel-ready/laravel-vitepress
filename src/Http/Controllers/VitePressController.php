<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VitePressController extends Controller
{
    protected string $docsPath;

    /**
     * @var array<string, string>
     */
    protected array $mimeTypes;

    public function __construct()
    {
        $this->docsPath = $this->resolveDocsPath();

        $this->mimeTypes = [
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'mjs' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'otf' => 'font/otf',
            'map' => 'application/json',
            'txt' => 'text/plain',
            'xml' => 'application/xml',
            'webmanifest' => 'application/manifest+json',
        ];
    }

    /**
     * Display the documentation index.
     */
    public function index(): Response|BinaryFileResponse
    {
        return $this->serve('index.html');
    }

    /**
     * Display a documentation page.
     */
    public function show(Request $request, ?string $path = null): Response|BinaryFileResponse
    {
        $path = $path ?? 'index.html';

        // Normalize path
        $path = $this->normalizePath($path);

        return $this->serve($path);
    }

    /**
     * Serve a file from the documentation directory.
     */
    protected function serve(string $path): Response|BinaryFileResponse
    {
        $filePath = $this->docsPath . '/' . $path;

        // Security: Prevent path traversal attacks
        $realPath = realpath($filePath);
        $realDocsPath = realpath($this->docsPath);

        if (! $realPath || ! $realDocsPath || ! str_starts_with($realPath, $realDocsPath)) {
            return $this->handleNotFound($path);
        }

        // Check if file exists
        if (! File::exists($filePath)) {
            return $this->handleNotFound($path);
        }

        // Determine MIME type
        $mimeType = $this->getMimeType($filePath);

        // Prepare headers
        $headers = array_merge(
            ['Content-Type' => $mimeType],
            $this->getCacheHeaders(),
            $this->getCorsHeaders(),
            config('vitepress.options.headers', [])
        );

        return response()->file($filePath, $headers);
    }

    /**
     * Handle 404 errors with SPA fallback.
     */
    protected function handleNotFound(string $path): Response|BinaryFileResponse
    {
        // SPA fallback: serve index.html for HTML routes
        if (config('vitepress.options.spa_fallback', true) && ! $this->isAssetPath($path)) {
            // Try to find a matching .html file first
            $htmlPath = $this->docsPath . '/' . rtrim($path, '/') . '.html';

            if (File::exists($htmlPath)) {
                return response()->file($htmlPath, [
                    'Content-Type' => 'text/html',
                ]);
            }

            // Try index.html in the directory
            $indexPath = $this->docsPath . '/' . rtrim($path, '/') . '/index.html';
            if (File::exists($indexPath)) {
                return response()->file($indexPath, [
                    'Content-Type' => 'text/html',
                ]);
            }

            // Fall back to root index.html
            $rootIndexPath = $this->docsPath . '/index.html';
            if (File::exists($rootIndexPath)) {
                return response()->file($rootIndexPath, [
                    'Content-Type' => 'text/html',
                ]);
            }
        }

        // Custom 404 handler
        if ($custom404 = config('vitepress.options.custom_404')) {
            return response()->view($custom404, [], 404);
        }

        abort(404, 'Documentation page not found');
    }

    /**
     * Normalize the requested path.
     */
    protected function normalizePath(string $path): string
    {
        // Remove leading/trailing slashes
        $path = trim($path, '/');

        // If no extension and not an asset, check for .html or directory index
        if (! str_contains($path, '.') && ! $this->isAssetPath($path)) {
            // First check if .html file exists
            if (File::exists($this->docsPath . '/' . $path . '.html')) {
                $path .= '.html';
            }
            // Then check if directory with index.html exists
            elseif (File::exists($this->docsPath . '/' . $path . '/index.html')) {
                $path .= '/index.html';
            }
        }

        return $path;
    }

    /**
     * Check if the path is an asset (not an HTML page).
     */
    protected function isAssetPath(string $path): bool
    {
        $assetExtensions = [
            'css',
            'js',
            'mjs',
            'json',
            'png',
            'jpg',
            'jpeg',
            'gif',
            'svg',
            'ico',
            'webp',
            'woff',
            'woff2',
            'ttf',
            'eot',
            'otf',
            'map',
        ];
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, $assetExtensions, true);
    }

    /**
     * Get the MIME type for a file.
     */
    protected function getMimeType(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return $this->mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Get cache control headers.
     */
    protected function getCacheHeaders(): array
    {
        if (! config('vitepress.assets.cache.enabled', true)) {
            return [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];
        }

        $maxAge = config('vitepress.assets.cache.max_age', 3600);

        return [
            'Cache-Control' => "public, max-age={$maxAge}",
        ];
    }

    /**
     * Get CORS headers if enabled.
     */
    protected function getCorsHeaders(): array
    {
        if (! config('vitepress.options.cors_enabled', false)) {
            return [];
        }

        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ];
    }

    /**
     * Resolve the documentation path based on storage configuration.
     */
    protected function resolveDocsPath(): string
    {
        $storage = config('vitepress.assets.storage', 'storage');
        $path = config('vitepress.assets.path', 'vitepress/docs');

        if ($storage === 'public') {
            return public_path($path);
        }

        // Default to storage (protected from direct access)
        return storage_path('app/' . $path);
    }
}
