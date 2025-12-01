<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use LaravelReady\VitePress\Tests\TestCase;

class VitePressAuthTest extends TestCase
{
    /** @test */
    public function it_allows_access_when_auth_is_disabled(): void
    {
        Config::set('vitepress.auth.enabled', false);

        $response = $this->get('/docs');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_redirects_unauthenticated_users_when_auth_is_enabled(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.redirect_unauthorized_to', '/login');

        $response = $this->get('/docs');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_allows_authenticated_users_to_access_docs(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.roles', []);
        Config::set('vitepress.auth.permissions', []);

        $user = $this->createMockUser();

        $response = $this->actingAs($user)->get('/docs');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_checks_user_roles_when_configured(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.roles', ['admin', 'developer']);
        Config::set('vitepress.auth.permissions', []);

        // User with matching role
        $adminUser = $this->createMockUser(['roles' => ['admin']]);
        $response = $this->actingAs($adminUser)->get('/docs');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_denies_users_without_required_roles(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.roles', ['admin']);
        Config::set('vitepress.auth.permissions', []);
        Config::set('vitepress.auth.redirect_unauthorized_to', '/login');

        // User without matching role
        $regularUser = $this->createMockUser(['roles' => ['user']]);
        $response = $this->actingAs($regularUser)->get('/docs');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_checks_user_permissions_when_configured(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.roles', []);
        Config::set('vitepress.auth.permissions', ['view-docs']);

        // User with matching permission
        $user = $this->createMockUser(['permissions' => ['view-docs']]);
        $response = $this->actingAs($user)->get('/docs');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_uses_custom_gate_when_configured(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.gate', 'view-documentation');
        Config::set('vitepress.auth.roles', []);
        Config::set('vitepress.auth.permissions', []);

        Gate::define('view-documentation', function ($user) {
            return true;
        });

        $user = $this->createMockUser();
        $response = $this->actingAs($user)->get('/docs');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_denies_access_when_gate_returns_false(): void
    {
        Config::set('vitepress.auth.enabled', true);
        Config::set('vitepress.auth.gate', 'view-documentation');
        Config::set('vitepress.auth.roles', []);
        Config::set('vitepress.auth.permissions', []);
        Config::set('vitepress.auth.redirect_unauthorized_to', '/login');

        Gate::define('view-documentation', function ($user) {
            return false;
        });

        $user = $this->createMockUser();
        $response = $this->actingAs($user)->get('/docs');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_returns_json_for_api_requests_when_unauthorized(): void
    {
        Config::set('vitepress.auth.enabled', true);

        $response = $this->getJson('/docs');

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Unauthorized access to documentation.',
        ]);
    }
}
