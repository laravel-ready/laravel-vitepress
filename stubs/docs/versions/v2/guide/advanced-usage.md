# Advanced Usage (v2)

## Custom Middleware

You can add additional middleware to protect documentation:

```php
// config/vitepress.php
'route' => [
    'middleware' => ['web', 'verified', 'subscription.active'],
],
```

## Custom 404 Page

Create a custom 404 view:

```php
// config/vitepress.php
'options' => [
    'custom_404' => 'errors.docs-404',
],
```

Create the view at `resources/views/errors/docs-404.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <h1>Documentation Page Not Found</h1>
    <p>The page you're looking for doesn't exist.</p>
    <a href="{{ route('vitepress.index') }}">Go to Documentation Home</a>
@endsection
```

## Custom Headers

Add security or custom headers:

```php
'options' => [
    'headers' => [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
    ],
],
```

## VitePress Theme Customization

Customize the VitePress theme in `.vitepress/config.js`:

```js
export default defineConfig({
    themeConfig: {
        // Custom logo
        logo: '/logo.svg',

        // Custom colors via CSS variables
        // Create .vitepress/theme/custom.css
    }
})
```
