# Laravel VitePress

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-ready/laravel-vitepress.svg?style=flat-square)](https://packagist.org/packages/laravel-ready/laravel-vitepress)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laravel-ready/laravel-vitepress/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laravel-ready/laravel-vitepress/actions?query=workflow%3Atests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-ready/laravel-vitepress.svg?style=flat-square)](https://packagist.org/packages/laravel-ready/laravel-vitepress)

Serve VitePress documentation in your Laravel application with full middleware support, authentication, and role-based access control.

## Features

- 🚀 Pre-built VitePress documentation (no build step required in production)
- 🔐 Laravel authentication and authorization integration
- 🛡️ Middleware support for route protection
- 👥 Role and permission-based access control (compatible with Spatie Laravel Permission)
- ⚙️ Fully configurable routing
- 📦 Easy installation and publishing
- 🎨 Customizable views and layouts
- 🔒 Path traversal protection
- 🔐 Protected storage by default (docs stored outside `public/` to enforce auth)
- 💾 Cache control headers
- 🌐 Multi-domain support

## Requirements

- PHP 8.1+
- Laravel 10.x, 11.x, or 12.x
- Node.js 20+ (for building documentation)

## Installation

Install the package via Composer:

```bash
composer require laravel-ready/laravel-vitepress
```

Run the installation command:

```bash
php artisan vitepress:install
```

This will publish:
- Configuration file to `config/vitepress.php`
- Pre-built documentation assets to `storage/app/vitepress/docs` (protected by default)

Visit `/docs` in your browser to see your documentation.

## Configuration

### Basic Setup

```php
// config/vitepress.php

return [
    'route' => [
        'prefix' => 'docs', // Access at /docs
        'middleware' => ['web'],
    ],
];
```

### Storage Location

By default, documentation is stored in `storage/` to prevent direct file access and ensure authentication is enforced. For public documentation without auth, you can store in `public/`:

```php
'assets' => [
    // 'storage' = protected (default), 'public' = directly accessible
    'storage' => 'storage',
    'path' => 'vitepress/docs',
],
```

**Important:** If you use authentication (`auth.enabled = true`), keep `storage` set to `'storage'`. Otherwise users can bypass auth by accessing files directly at `/vitepress/docs/index.html`.

### With Authentication

Require users to be logged in:

```php
'auth' => [
    'enabled' => true,
    'middleware' => ['auth'],
],
```

### With Role-Based Access

Works with [spatie/laravel-permission](https://github.com/spatie/laravel-permission) (optional):

```php
'auth' => [
    'enabled' => true,
    'middleware' => ['auth'],
    'roles' => ['admin', 'developer'],
],
```

### With Permission-Based Access

```php
'auth' => [
    'enabled' => true,
    'middleware' => ['auth'],
    'permissions' => ['view-documentation'],
],
```

> **Note:** Role and permission checks use `hasAnyRole()` and `hasAnyPermission()` methods from Spatie Laravel Permission. If the package is not installed, these checks are automatically skipped. The package works without Spatie - you can use basic auth or custom gates instead.

### Custom Domain

Serve documentation from a subdomain:

```php
'route' => [
    'domain' => 'docs.example.com',
    'prefix' => '',
],
```

### Custom Gate

Define a custom gate for complex authorization:

```php
// In AuthServiceProvider
Gate::define('view-documentation', function ($user) {
    return $user->hasActiveSubscription();
});

// config/vitepress.php
'auth' => [
    'enabled' => true,
    'gate' => 'view-documentation',
],
```

## Usage

### Accessing Documentation

After installation, your documentation is available at:

```
https://your-app.com/docs
```

### Using the Facade

```php
use LaravelReady\VitePress\Facades\VitePress;

// Check if VitePress is enabled
if (VitePress::isEnabled()) {
    // ...
}

// Get the documentation URL
$url = VitePress::getRouteUrl();

// Check if current user can access docs
if (VitePress::canAccess()) {
    // Show documentation link
}

// Get configuration
$config = VitePress::getConfig();
$prefix = VitePress::getConfig('route.prefix');
```

### In Blade Templates

```blade
@if(VitePress::isEnabled() && VitePress::canAccess())
    <a href="{{ VitePress::getRouteUrl() }}">Documentation</a>
@endif
```

## Customization

### Publishing VitePress Source

To customize the documentation:

```bash
php artisan vitepress:publish --stubs
```

This will publish VitePress source files to `resources/docs/`.

### Building Documentation

After making changes, rebuild:

```bash
php artisan vitepress:build
```

Or use npm directly:

```bash
cd resources/docs
npm install
npm run docs:build
```

### Changing Route Prefix

If you change `VITEPRESS_ROUTE_PREFIX` (e.g., from `/docs` to `/documentation`), you must rebuild VitePress with the matching base URL:

```bash
cd resources/docs
VITEPRESS_BASE=/documentation/ npm run docs:build
```

Then copy the built files:

```bash
php artisan vitepress:build
```

The VitePress `base` config must match your Laravel route prefix, otherwise assets will 404.

### Publishing Other Resources

```bash
# Publish configuration only
php artisan vitepress:publish --config

# Publish assets only
php artisan vitepress:publish --assets

# Publish views only
php artisan vitepress:publish --views

# Publish everything
php artisan vitepress:publish --all
```

## Artisan Commands

| Command | Description |
|---------|-------------|
| `vitepress:install` | Install the package (publishes config and assets) |
| `vitepress:publish` | Publish specific resources |
| `vitepress:build` | Build VitePress documentation |

## Environment Variables

```env
VITEPRESS_ENABLED=true
VITEPRESS_ROUTE_PREFIX=docs
VITEPRESS_DOMAIN=
VITEPRESS_AUTH_ENABLED=false
VITEPRESS_ALLOWED_ROLES=admin,developer
VITEPRESS_ALLOWED_PERMISSIONS=view-docs
VITEPRESS_GATE=
VITEPRESS_STORAGE=storage
VITEPRESS_ASSETS_PATH=vitepress/docs
VITEPRESS_CACHE_ENABLED=true
VITEPRESS_CACHE_MAX_AGE=3600
```

## Advanced Usage

### Custom Headers

Add security or custom headers:

```php
'options' => [
    'headers' => [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
    ],
],
```

### Custom 404 Page

```php
'options' => [
    'custom_404' => 'errors.docs-404',
],
```

### CORS Support

Enable CORS for API documentation:

```php
'options' => [
    'cors_enabled' => true,
],
```

### SPA Fallback

The package includes SPA fallback routing by default. Disable it for traditional page routing:

```php
'options' => [
    'spa_fallback' => false,
],
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security related issues, please email security@laravelready.com instead of using the issue tracker.

## Credits

- [Laravel Ready](https://github.com/laravel-ready)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
