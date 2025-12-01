# API Reference

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
| `getConfig(?string $key)` | `mixed` | Get configuration value(s) |
| `isAuthEnabled()` | `bool` | Check if authentication is enabled |
| `canAccess()` | `bool` | Check if current user can access docs |

## Artisan Commands

### vitepress:install

Install the Laravel VitePress package.

```bash
php artisan vitepress:install [--force]
```

| Option | Description |
|--------|-------------|
| `--force` | Overwrite existing files |

### vitepress:publish

Publish Laravel VitePress resources.

```bash
php artisan vitepress:publish [--config] [--assets] [--stubs] [--views] [--all] [--force]
```

| Option | Description |
|--------|-------------|
| `--config` | Publish configuration only |
| `--assets` | Publish assets only |
| `--stubs` | Publish VitePress source stubs |
| `--views` | Publish views only |
| `--all` | Publish all resources |
| `--force` | Overwrite existing files |

### vitepress:build

Build VitePress documentation.

```bash
php artisan vitepress:build [--source=] [--output=] [--install]
```

| Option | Description |
|--------|-------------|
| `--source` | Source directory path |
| `--output` | Output directory path |
| `--install` | Install npm dependencies before building |
