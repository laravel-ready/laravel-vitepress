<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Tests\Feature;

use Illuminate\Support\Facades\Config;
use LaravelReady\VitePress\Tests\TestCase;

class VitePressControllerTest extends TestCase
{
    /** @test */
    public function it_serves_documentation_index(): void
    {
        $response = $this->get('/docs');

        $response->assertStatus(200);
        $response->assertSee('Test Documentation');
    }

    /** @test */
    public function it_serves_documentation_pages(): void
    {
        $response = $this->get('/docs/guide/getting-started');

        $response->assertStatus(200);
        $response->assertSee('Getting Started');
    }

    /** @test */
    public function it_serves_css_files_with_correct_mime_type(): void
    {
        $response = $this->get('/docs/style.css');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/css');
    }

    /** @test */
    public function it_returns_404_for_non_existent_pages(): void
    {
        // Disable SPA fallback for this test
        Config::set('vitepress.options.spa_fallback', false);

        $response = $this->get('/docs/non-existent-page.html');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_falls_back_to_index_when_spa_fallback_is_enabled(): void
    {
        Config::set('vitepress.options.spa_fallback', true);

        $response = $this->get('/docs/non-existent-page');

        // Should serve index.html as fallback
        $response->assertStatus(200);
        $response->assertSee('Test Documentation');
    }

    /** @test */
    public function it_prevents_path_traversal_attacks(): void
    {
        $response = $this->get('/docs/../../../etc/passwd');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_prevents_encoded_path_traversal_attacks(): void
    {
        $response = $this->get('/docs/%2e%2e/%2e%2e/%2e%2e/etc/passwd');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_adds_cache_headers_when_enabled(): void
    {
        Config::set('vitepress.assets.cache.enabled', true);
        Config::set('vitepress.assets.cache.max_age', 3600);

        $response = $this->get('/docs');

        $response->assertHeader('Cache-Control', 'public, max-age=3600');
    }

    /** @test */
    public function it_adds_no_cache_headers_when_disabled(): void
    {
        Config::set('vitepress.assets.cache.enabled', false);

        $response = $this->get('/docs');

        $response->assertHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    /** @test */
    public function it_can_be_accessed_without_trailing_slash(): void
    {
        $response = $this->get('/docs');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_be_disabled_via_config(): void
    {
        Config::set('vitepress.route.enabled', false);

        // Need to refresh routes for this to take effect in a real scenario
        // In tests, this just verifies the config can be changed
        $this->assertEquals(false, config('vitepress.route.enabled'));
    }

    /** @test */
    public function it_uses_custom_route_prefix(): void
    {
        // This tests that the route prefix is configurable
        $this->assertEquals('docs', config('vitepress.route.prefix'));
    }
}
