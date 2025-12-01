<?php

it('has default route configuration', function () {
    expect(config('vitepress.route.enabled'))->toBeTrue();
    expect(config('vitepress.route.prefix'))->toBe('docs');
    expect(config('vitepress.route.name'))->toBe('vitepress');
    expect(config('vitepress.route.domain'))->toBeNull();
    expect(config('vitepress.route.middleware'))->toBe(['web']);
});

it('has default auth configuration', function () {
    expect(config('vitepress.auth.enabled'))->toBeFalse();
    expect(config('vitepress.auth.middleware'))->toBe(['auth']);
    expect(config('vitepress.auth.roles'))->toBe([]);
    expect(config('vitepress.auth.permissions'))->toBe([]);
    expect(config('vitepress.auth.gate'))->toBeNull();
    expect(config('vitepress.auth.redirect_unauthorized_to'))->toBe('/login');
});

it('has default assets configuration', function () {
    expect(config('vitepress.assets.storage'))->toBe('storage');
    expect(config('vitepress.assets.path'))->toBe('vitepress/docs');
    expect(config('vitepress.assets.cache.enabled'))->toBeTrue();
    expect(config('vitepress.assets.cache.max_age'))->toBe(3600);
});

it('has default options configuration', function () {
    expect(config('vitepress.options.spa_fallback'))->toBeTrue();
    expect(config('vitepress.options.custom_404'))->toBeNull();
    expect(config('vitepress.options.cors_enabled'))->toBeFalse();
    expect(config('vitepress.options.headers'))->toBe([]);
});

it('has default build configuration', function () {
    expect(config('vitepress.build.source_path'))->toBe(base_path('resources/docs'));
    expect(config('vitepress.build.base_url'))->toBe('/docs/');
});

it('can override route configuration', function () {
    config(['vitepress.route.prefix' => 'documentation']);

    expect(config('vitepress.route.prefix'))->toBe('documentation');
});

it('can override auth configuration', function () {
    config(['vitepress.auth.enabled' => true]);
    config(['vitepress.auth.roles' => ['admin', 'developer']]);

    expect(config('vitepress.auth.enabled'))->toBeTrue();
    expect(config('vitepress.auth.roles'))->toBe(['admin', 'developer']);
});

it('can override cache configuration', function () {
    config(['vitepress.assets.cache.enabled' => false]);
    config(['vitepress.assets.cache.max_age' => 7200]);

    expect(config('vitepress.assets.cache.enabled'))->toBeFalse();
    expect(config('vitepress.assets.cache.max_age'))->toBe(7200);
});

it('can override storage configuration', function () {
    config(['vitepress.assets.storage' => 'public']);

    expect(config('vitepress.assets.storage'))->toBe('public');
});
