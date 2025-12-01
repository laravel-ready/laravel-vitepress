# Basic Usage (v1)

## Accessing Documentation

After installation, your documentation is available at:

```
https://your-app.test/docs
```

## Using the Facade

The `VitePress` facade provides helpful methods:

```php
use LaravelReady\VitePress\Facades\VitePress;

// Check if VitePress is enabled
if (VitePress::isEnabled()) {
    // ...
}

// Get the documentation URL
$url = VitePress::getRouteUrl();
```

## Linking to Documentation

In your Blade views:

```blade
@if(VitePress::isEnabled())
    <a href="{{ VitePress::getRouteUrl() }}">Documentation</a>
@endif
```

## Customizing Documentation

1. Publish the VitePress source files:

```bash
php artisan vitepress:publish --stubs
```

2. Edit files in `resources/docs/`

3. Rebuild the documentation:

```bash
php artisan vitepress:build
```
