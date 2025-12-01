# Configuration (v1)

All configuration options are available in `config/vitepress.php`.

## Route Configuration

```php
'route' => [
    'enabled' => env('VITEPRESS_ENABLED', true),
    'prefix' => env('VITEPRESS_ROUTE_PREFIX', 'docs'),
    'middleware' => ['web'],
],
```

### Route Prefix

Change the URL where documentation is served:

```php
'prefix' => 'documentation', // Access at /documentation
```

## Authentication

Enable authentication to protect your documentation:

```php
'auth' => [
    'enabled' => true,
    'middleware' => ['auth'],
],
```

## Environment Variables

```env
VITEPRESS_ENABLED=true
VITEPRESS_ROUTE_PREFIX=docs
VITEPRESS_AUTH_ENABLED=false
```
