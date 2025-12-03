# Advanced Usage

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

## CORS Support

Enable CORS for API documentation:

```php
'options' => [
    'cors_enabled' => true,
],
```

## Custom Headers

Add security or custom headers:

```php
'options' => [
    'headers' => [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
    ],
],
```

## Multiple Documentation Sets

For multiple documentation sets, you can extend the controller:

```php
namespace App\Http\Controllers;

use LaravelReady\VitePress\Http\Controllers\VitePressController;

class ApiDocsController extends VitePressController
{
    public function __construct()
    {
        $this->docsPath = public_path('api-docs');
        parent::__construct();
    }
}
```

Register routes in `routes/web.php`:

```php
Route::prefix('api-docs')->group(function () {
    Route::get('/', [ApiDocsController::class, 'index']);
    Route::get('/{path}', [ApiDocsController::class, 'show'])->where('path', '.*');
});
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

Create `.vitepress/theme/index.js`:

```js
import DefaultTheme from 'vitepress/theme'
import './custom.css'

export default DefaultTheme
```

## CI/CD Integration

Use GitHub Actions to automatically build documentation:

```yaml
name: Build Documentation

on:
  push:
    paths:
      - 'resources/docs/**'

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
      - run: npm ci
      - run: npm run build
      - uses: stefanzweifel/git-auto-commit-action@v5
        with:
          commit_message: 'chore: rebuild documentation'
          file_pattern: 'public/vendor/laravel-vitepress/docs/*'
```
