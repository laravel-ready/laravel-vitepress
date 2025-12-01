# Configuration (v2)

All configuration options are available in `config/vitepress.php`.

## Route Configuration

```php
'route' => [
    'enabled' => env('VITEPRESS_ENABLED', true),
    'prefix' => env('VITEPRESS_ROUTE_PREFIX', 'docs'),
    'domain' => env('VITEPRESS_DOMAIN', null),
    'name' => env('VITEPRESS_ROUTE_NAME', 'vitepress'),
    'middleware' => ['web'],
],
```

### Route Prefix

Change the URL where documentation is served:

```php
'prefix' => 'documentation', // Access at /documentation
```

### Custom Domain

Serve documentation from a subdomain:

```php
'domain' => 'docs.example.com',
'prefix' => '', // Access at docs.example.com/
```

## Authentication

Enable authentication to protect your documentation:

```php
'auth' => [
    'enabled' => true,
    'middleware' => ['auth'],
    'roles' => ['admin', 'developer'],
    'permissions' => ['view-docs'],
    'redirect_unauthorized_to' => '/login',
],
```

### Role-Based Access

Requires [spatie/laravel-permission](https://github.com/spatie/laravel-permission):

```php
'auth' => [
    'enabled' => true,
    'roles' => ['admin', 'developer'],
],
```

### Permission-Based Access

```php
'auth' => [
    'enabled' => true,
    'permissions' => ['view-documentation'],
],
```

## Cache Configuration

Control browser caching for documentation assets:

```php
'assets' => [
    'cache' => [
        'enabled' => true,
        'max_age' => 3600, // seconds
    ],
],
```

## Environment Variables

```env
VITEPRESS_ENABLED=true
VITEPRESS_ROUTE_PREFIX=docs
VITEPRESS_DOMAIN=
VITEPRESS_AUTH_ENABLED=false
VITEPRESS_ALLOWED_ROLES=admin,developer
VITEPRESS_CACHE_ENABLED=true
VITEPRESS_CACHE_MAX_AGE=3600
```
