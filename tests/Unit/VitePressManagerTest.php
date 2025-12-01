<?php

use Illuminate\Support\Facades\Config;
use LaravelReady\VitePress\Facades\VitePress;

it('can check if enabled', function () {
    Config::set('vitepress.route.enabled', true);
    expect(VitePress::isEnabled())->toBeTrue();

    Config::set('vitepress.route.enabled', false);
    expect(VitePress::isEnabled())->toBeFalse();
});

it('can get docs path', function () {
    $path = VitePress::getDocsPath();

    expect($path)->toContain('vitepress/docs');
});

it('can get route path', function () {
    expect(VitePress::getRoutePath())->toBe('docs');

    Config::set('vitepress.route.prefix', 'documentation');
    expect(VitePress::getRoutePath())->toBe('documentation');
});

it('can get route url', function () {
    $url = VitePress::getRouteUrl();

    expect($url)->toContain('docs');
});

it('can get config', function () {
    $config = VitePress::getConfig();

    expect($config)->toBeArray();
    expect($config)->toHaveKey('route');
    expect($config)->toHaveKey('auth');
    expect($config)->toHaveKey('assets');
    expect($config)->toHaveKey('build');
    expect($config)->toHaveKey('options');
});

it('can get specific config value', function () {
    $prefix = VitePress::getConfig('route.prefix');

    expect($prefix)->toBe('docs');
});

it('can check if auth is enabled', function () {
    Config::set('vitepress.auth.enabled', false);
    expect(VitePress::isAuthEnabled())->toBeFalse();

    Config::set('vitepress.auth.enabled', true);
    expect(VitePress::isAuthEnabled())->toBeTrue();
});

it('returns true for can access when auth is disabled', function () {
    Config::set('vitepress.auth.enabled', false);

    expect(VitePress::canAccess())->toBeTrue();
});

it('returns false for can access when not authenticated', function () {
    Config::set('vitepress.auth.enabled', true);

    expect(VitePress::canAccess())->toBeFalse();
});

it('can get storage type', function () {
    Config::set('vitepress.assets.storage', 'storage');
    expect(VitePress::getConfig('assets.storage'))->toBe('storage');

    Config::set('vitepress.assets.storage', 'public');
    expect(VitePress::getConfig('assets.storage'))->toBe('public');
});
