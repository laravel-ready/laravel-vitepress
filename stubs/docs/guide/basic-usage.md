# Basic Usage

## Accessing Documentation

After installation, your documentation is available at:

```txt
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

// Check if current user can access docs
if (VitePress::canAccess()) {
    // Show documentation link
}

// Get configuration
$config = VitePress::getConfig();
$prefix = VitePress::getConfig('route.prefix');
```

## Linking to Documentation

In your Blade views:

```blade
@if(VitePress::isEnabled())
    <a href="{{ VitePress::getRouteUrl() }}">Documentation</a>
@endif
```

Or with conditional access:

```blade
@if(VitePress::canAccess())
    <a href="{{ route('vitepress.index') }}">Documentation</a>
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

## Adding New Pages

Create a new Markdown file in `resources/docs/`:

```markdown
# My New Page

Content goes here...
```

Update the sidebar in `.vitepress/config.js`:

```js
sidebar: [
    {
        text: 'Guide',
        items: [
            { text: 'New Page', link: '/guide/new-page' }
        ]
    }
]
```
