<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;

it('allows access when auth is disabled', function () {
    Config::set('vitepress.auth.enabled', false);

    $response = $this->get('/docs');

    $response->assertStatus(200);
});

it('redirects unauthenticated users when auth is enabled', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.redirect_unauthorized_to', '/login');

    $response = $this->get('/docs');

    $response->assertRedirect('/login');
});

it('allows authenticated users to access docs', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', []);
    Config::set('vitepress.auth.permissions', []);

    $user = createMockUser();

    $response = $this->actingAs($user)->get('/docs');

    $response->assertStatus(200);
});

it('checks user roles when configured', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin', 'developer']);
    Config::set('vitepress.auth.permissions', []);

    $adminUser = createMockUser(['roles' => ['admin']]);

    $response = $this->actingAs($adminUser)->get('/docs');

    $response->assertStatus(200);
});

it('denies users without required roles', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin']);
    Config::set('vitepress.auth.permissions', []);
    Config::set('vitepress.auth.redirect_unauthorized_to', '/login');

    $regularUser = createMockUser(['roles' => ['user']]);

    $response = $this->actingAs($regularUser)->get('/docs');

    $response->assertRedirect('/login');
});

it('checks user permissions when configured', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', []);
    Config::set('vitepress.auth.permissions', ['view-docs']);

    $user = createMockUser(['permissions' => ['view-docs']]);

    $response = $this->actingAs($user)->get('/docs');

    $response->assertStatus(200);
});

it('uses custom gate when configured', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.gate', 'view-documentation');
    Config::set('vitepress.auth.roles', []);
    Config::set('vitepress.auth.permissions', []);

    Gate::define('view-documentation', fn ($user) => true);

    $user = createMockUser();

    $response = $this->actingAs($user)->get('/docs');

    $response->assertStatus(200);
});

it('denies access when gate returns false', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.gate', 'view-documentation');
    Config::set('vitepress.auth.roles', []);
    Config::set('vitepress.auth.permissions', []);
    Config::set('vitepress.auth.redirect_unauthorized_to', '/login');

    Gate::define('view-documentation', fn ($user) => false);

    $user = createMockUser();

    $response = $this->actingAs($user)->get('/docs');

    $response->assertRedirect('/login');
});

it('returns json for api requests when unauthorized', function () {
    Config::set('vitepress.auth.enabled', true);

    $response = $this->getJson('/docs');

    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'Unauthorized access to documentation.',
    ]);
});
