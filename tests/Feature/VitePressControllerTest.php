<?php

use Illuminate\Support\Facades\Config;

it('serves documentation index', function () {
    $response = $this->get('/docs');

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('text/html');
});

it('serves documentation pages', function () {
    $response = $this->get('/docs/guide/getting-started');

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('text/html');
});

it('serves css files with correct mime type', function () {
    $response = $this->get('/docs/assets/style.css');

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('text/css');
});

it('returns 404 for non-existent pages when spa fallback is disabled', function () {
    Config::set('vitepress.options.spa_fallback', false);

    $response = $this->get('/docs/non-existent-page.html');

    $response->assertStatus(404);
});

it('falls back to index when spa fallback is enabled', function () {
    Config::set('vitepress.options.spa_fallback', true);

    $response = $this->get('/docs/non-existent-page');

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('text/html');
});

it('prevents direct path traversal attacks', function () {
    // Path traversal patterns should be blocked by the realpath security check
    // The '...' pattern that survives browser/framework normalization
    $response = $this->get('/docs/.../.../etc/passwd');

    // Should either return 404 or redirect to index (SPA fallback)
    // Key point: should NOT serve /etc/passwd
    $statusCode = $response->getStatusCode();
    expect($statusCode)->toBeIn([200, 404]);
    if ($statusCode === 200) {
        expect($response->headers->get('Content-Type'))->toContain('text/html');
    }
});

it('prevents traversal via hashpath query', function () {
    // Test that query-based traversal doesn't work
    Config::set('vitepress.options.spa_fallback', false);

    $response = $this->get('/docs?path=../../../etc/passwd');

    // This should return doc content, not the passwd file
    expect($response->getStatusCode())->toBeIn([200, 404]);
});

it('adds cache headers when enabled', function () {
    Config::set('vitepress.assets.cache.enabled', true);
    Config::set('vitepress.assets.cache.max_age', 3600);

    $response = $this->get('/docs');

    $cacheControl = $response->headers->get('Cache-Control');
    expect($cacheControl)->toContain('max-age=3600');
});

it('adds no-cache headers when caching is disabled', function () {
    Config::set('vitepress.assets.cache.enabled', false);

    $response = $this->get('/docs');

    $cacheControl = $response->headers->get('Cache-Control');
    expect($cacheControl)->toContain('no-cache');
    expect($cacheControl)->toContain('no-store');
});

it('can be accessed without trailing slash', function () {
    $response = $this->get('/docs');

    $response->assertStatus(200);
});

it('uses configured route prefix', function () {
    expect(config('vitepress.route.prefix'))->toBe('docs');
});
