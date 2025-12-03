<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use LaravelReady\VitePress\Http\Middleware\VitePressAuth;

beforeEach(function () {
    Config::set('vitepress.auth.enabled', false);
    Config::set('vitepress.auth.roles', []);
    Config::set('vitepress.auth.permissions', []);
    Config::set('vitepress.auth.gate', null);
    Config::set('vitepress.auth.redirect_unauthorized_to', '/login');
});

it('passes through when auth is disabled', function () {
    Config::set('vitepress.auth.enabled', false);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getContent())->toBe('OK');
});

it('denies unauthenticated users when auth is enabled', function () {
    Config::set('vitepress.auth.enabled', true);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->isRedirection())->toBeTrue();
});

it('allows authenticated users when no roles or permissions configured', function () {
    Config::set('vitepress.auth.enabled', true);

    $user = createMockUser();
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getContent())->toBe('OK');
});

it('allows users with matching roles', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin', 'developer']);

    $user = createMockUser(['roles' => ['admin']]);
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getContent())->toBe('OK');
});

it('denies users without matching roles', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin']);

    $user = createMockUser(['roles' => ['user']]);
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->isRedirection())->toBeTrue();
});

it('allows users with matching permissions', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.permissions', ['view-docs']);

    $user = createMockUser(['permissions' => ['view-docs']]);
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getContent())->toBe('OK');
});

it('denies users without matching permissions', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.permissions', ['view-docs']);

    $user = createMockUser(['permissions' => ['other-permission']]);
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->isRedirection())->toBeTrue();
});

it('allows access when gate returns true', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.gate', 'view-documentation');

    Gate::define('view-documentation', fn ($user) => true);

    $user = createMockUser();
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getContent())->toBe('OK');
});

it('denies access when gate returns false', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.gate', 'view-documentation');

    Gate::define('view-documentation', fn ($user) => false);

    $user = createMockUser();
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->isRedirection())->toBeTrue();
});

it('returns json response for api requests when unauthorized', function () {
    Config::set('vitepress.auth.enabled', true);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');
    $request->headers->set('Accept', 'application/json');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getStatusCode())->toBe(403);
    expect($response->getContent())->toContain('Unauthorized access to documentation');
});

it('redirects to configured url when unauthorized', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.redirect_unauthorized_to', '/custom-login');

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->isRedirection())->toBeTrue();
    expect($response->headers->get('Location'))->toContain('/custom-login');
});

it('requires both roles and permissions when both configured', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin']);
    Config::set('vitepress.auth.permissions', ['view-docs']);

    // User has role but not permission
    $user = createMockUser(['roles' => ['admin'], 'permissions' => []]);
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->isRedirection())->toBeTrue();
});

it('allows when user has both required roles and permissions', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin']);
    Config::set('vitepress.auth.permissions', ['view-docs']);

    $user = createMockUser(['roles' => ['admin'], 'permissions' => ['view-docs']]);
    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    expect($response->getContent())->toBe('OK');
});

it('skips role check when user does not have hasAnyRole method', function () {
    Config::set('vitepress.auth.enabled', true);
    Config::set('vitepress.auth.roles', ['admin']);

    // Create a user without hasAnyRole method
    $user = new class implements \Illuminate\Contracts\Auth\Authenticatable
    {
        public function getAuthIdentifierName(): string
        {
            return 'id';
        }

        public function getAuthIdentifier(): mixed
        {
            return 1;
        }

        public function getAuthPassword(): string
        {
            return 'password';
        }

        public function getAuthPasswordName(): string
        {
            return 'password';
        }

        public function getRememberToken(): ?string
        {
            return null;
        }

        public function setRememberToken($value): void
        {
        }

        public function getRememberTokenName(): string
        {
            return 'remember_token';
        }
    };

    $this->actingAs($user);

    $middleware = new VitePressAuth();
    $request = Request::create('/docs', 'GET');

    $response = $middleware->handle($request, fn () => new Response('OK'));

    // Should pass because user doesn't have hasAnyRole method
    expect($response->getContent())->toBe('OK');
});
