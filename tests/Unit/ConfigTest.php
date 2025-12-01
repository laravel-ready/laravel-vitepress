<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Tests\Unit;

use LaravelReady\VitePress\Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_has_default_route_configuration(): void
    {
        $this->assertTrue(config('vitepress.route.enabled'));
        $this->assertEquals('docs', config('vitepress.route.prefix'));
        $this->assertEquals('vitepress', config('vitepress.route.name'));
        $this->assertNull(config('vitepress.route.domain'));
        $this->assertEquals(['web'], config('vitepress.route.middleware'));
    }

    /** @test */
    public function it_has_default_auth_configuration(): void
    {
        $this->assertFalse(config('vitepress.auth.enabled'));
        $this->assertEquals(['auth'], config('vitepress.auth.middleware'));
        $this->assertEquals([], config('vitepress.auth.roles'));
        $this->assertEquals([], config('vitepress.auth.permissions'));
        $this->assertNull(config('vitepress.auth.gate'));
        $this->assertEquals('/login', config('vitepress.auth.redirect_unauthorized_to'));
    }

    /** @test */
    public function it_has_default_assets_configuration(): void
    {
        $this->assertEquals('vendor/laravel-vitepress/docs', config('vitepress.assets.path'));
        $this->assertEquals('public', config('vitepress.assets.disk'));
        $this->assertTrue(config('vitepress.assets.cache.enabled'));
        $this->assertEquals(3600, config('vitepress.assets.cache.max_age'));
    }

    /** @test */
    public function it_has_default_options_configuration(): void
    {
        $this->assertTrue(config('vitepress.options.spa_fallback'));
        $this->assertNull(config('vitepress.options.custom_404'));
        $this->assertFalse(config('vitepress.options.cors_enabled'));
        $this->assertEquals([], config('vitepress.options.headers'));
    }

    /** @test */
    public function it_can_override_configuration(): void
    {
        config(['vitepress.route.prefix' => 'documentation']);

        $this->assertEquals('documentation', config('vitepress.route.prefix'));
    }

    /** @test */
    public function it_can_override_auth_configuration(): void
    {
        config(['vitepress.auth.enabled' => true]);
        config(['vitepress.auth.roles' => ['admin', 'developer']]);

        $this->assertTrue(config('vitepress.auth.enabled'));
        $this->assertEquals(['admin', 'developer'], config('vitepress.auth.roles'));
    }

    /** @test */
    public function it_can_override_cache_configuration(): void
    {
        config(['vitepress.assets.cache.enabled' => false]);
        config(['vitepress.assets.cache.max_age' => 7200]);

        $this->assertFalse(config('vitepress.assets.cache.enabled'));
        $this->assertEquals(7200, config('vitepress.assets.cache.max_age'));
    }
}
