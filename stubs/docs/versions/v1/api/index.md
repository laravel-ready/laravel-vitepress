# API Reference (v1)

## Overview

This section documents the available API methods and classes.

## VitePress Facade

The `VitePress` facade provides convenient access to the VitePress manager.

### Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `isEnabled()` | `bool` | Check if VitePress routing is enabled |
| `getDocsPath()` | `string` | Get the documentation assets path |
| `getRoutePath()` | `string` | Get the route prefix |
| `getRouteUrl()` | `string` | Get the full URL to documentation |

## Artisan Commands

### vitepress:install

Install the Laravel VitePress package.

```bash
php artisan vitepress:install [--force]
```

### vitepress:build

Build VitePress documentation.

```bash
php artisan vitepress:build
```
