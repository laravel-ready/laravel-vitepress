<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Tests\Unit;

use Illuminate\Support\Facades\Config;
use LaravelReady\VitePress\Facades\VitePress;
use LaravelReady\VitePress\Tests\TestCase;

class VitePressManagerTest extends TestCase
{
    /** @test */
    public function it_can_check_if_enabled(): void
    {
        Config::set('vitepress.route.enabled', true);
        $this->assertTrue(VitePress::isEnabled());

        Config::set('vitepress.route.enabled', false);
        $this->assertFalse(VitePress::isEnabled());
    }

    /** @test */
    public function it_can_get_docs_path(): void
    {
        $path = VitePress::getDocsPath();

        $this->assertStringContainsString('vendor/laravel-vitepress/docs', $path);
    }

    /** @test */
    public function it_can_get_route_path(): void
    {
        $this->assertEquals('docs', VitePress::getRoutePath());

        Config::set('vitepress.route.prefix', 'documentation');
        $this->assertEquals('documentation', VitePress::getRoutePath());
    }

    /** @test */
    public function it_can_get_route_url(): void
    {
        $url = VitePress::getRouteUrl();

        $this->assertStringContainsString('docs', $url);
    }

    /** @test */
    public function it_can_get_config(): void
    {
        $config = VitePress::getConfig();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('route', $config);
        $this->assertArrayHasKey('auth', $config);
        $this->assertArrayHasKey('assets', $config);
    }

    /** @test */
    public function it_can_get_specific_config_value(): void
    {
        $prefix = VitePress::getConfig('route.prefix');

        $this->assertEquals('docs', $prefix);
    }

    /** @test */
    public function it_can_check_if_auth_is_enabled(): void
    {
        Config::set('vitepress.auth.enabled', false);
        $this->assertFalse(VitePress::isAuthEnabled());

        Config::set('vitepress.auth.enabled', true);
        $this->assertTrue(VitePress::isAuthEnabled());
    }

    /** @test */
    public function it_returns_true_for_can_access_when_auth_is_disabled(): void
    {
        Config::set('vitepress.auth.enabled', false);

        $this->assertTrue(VitePress::canAccess());
    }

    /** @test */
    public function it_returns_false_for_can_access_when_not_authenticated(): void
    {
        Config::set('vitepress.auth.enabled', true);

        $this->assertFalse(VitePress::canAccess());
    }
}
