# Methods Reference (v1)

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

---

### getRoutePath()

Get the route prefix configured for documentation.

```php
public function getRoutePath(): string
```

**Returns:** `string` - The route prefix.

---

### getRouteUrl()

Get the full URL to the documentation.

```php
public function getRouteUrl(): string
```

**Returns:** `string` - The full URL.
