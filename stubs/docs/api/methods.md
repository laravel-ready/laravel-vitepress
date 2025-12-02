# Methods Reference

## VitePressManager

The `VitePressManager` class handles all VitePress operations.

### isEnabled()

Check if VitePress routing is enabled.

```php
public function isEnabled(): bool
```

**Returns:** `bool` - True if enabled, false otherwise.

**Example:**

```php
use LaravelReady\VitePress\Facades\VitePress;

if (VitePress::isEnabled()) {
    echo "VitePress is enabled";
}
```

---

### getDocsPath()

Get the absolute path to the documentation assets directory.

```php
public function getDocsPath(): string
```

**Returns:** `string` - The absolute path.

**Example:**

```php
$path = VitePress::getDocsPath();
// /var/www/html/public/vendor/laravel-vitepress/docs
```

---

### getRoutePath()

Get the route prefix configured for documentation.

```php
public function getRoutePath(): string
```

**Returns:** `string` - The route prefix.

**Example:**

```php
$prefix = VitePress::getRoutePath();
// "docs"
```

---

### getRouteUrl()

Get the full URL to the documentation.

```php
public function getRouteUrl(): string
```

**Returns:** `string` - The full URL.

**Example:**

```php
$url = VitePress::getRouteUrl();
// "https://example.com/docs"
```

---

### getConfig()

Get configuration value(s).

```php
public function getConfig(?string $key = null): mixed
```

**Parameters:**

- `$key` (optional) - Dot-notation config key

**Returns:** `mixed` - The configuration value or array.

**Example:**

```php
// Get all configuration
$config = VitePress::getConfig();

// Get specific value
$prefix = VitePress::getConfig('route.prefix');
$cacheEnabled = VitePress::getConfig('assets.cache.enabled');
```

---

### isAuthEnabled()

Check if authentication is enabled for documentation.

```php
public function isAuthEnabled(): bool
```

**Returns:** `bool` - True if authentication is enabled.

**Example:**

```php
if (VitePress::isAuthEnabled()) {
    // Show login prompt
}
```

---

### canAccess()

Check if the current user can access documentation.

```php
public function canAccess(): bool
```

**Returns:** `bool` - True if the user has access.

**Example:**

```php
@if (VitePress::canAccess())
    <a href="{{ VitePress::getRouteUrl() }}">View Docs</a>
@else
    <span>Documentation requires authentication</span>
@endif
```

---

### getSourcePath()

Get the VitePress source files path.

```php
public function getSourcePath(): string
```

**Returns:** `string` - The source files path.

---

### getOutputPath()

Get the build output path.

```php
public function getOutputPath(): string
```

**Returns:** `string` - The output path.

---

### assetsExist()

Check if documentation assets have been published.

```php
public function assetsExist(): bool
```

**Returns:** `bool` - True if assets exist.

**Example:**

```php
if (!VitePress::assetsExist()) {
    Artisan::call('vitepress:publish --assets');
}
```
