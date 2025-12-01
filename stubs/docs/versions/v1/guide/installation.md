# Installation (v1)

## Requirements

- PHP 8.0+
- Laravel 9.x or 10.x
- Node.js 16+ (for building documentation)

## Installation via Composer

```bash
composer require laravel-ready/laravel-vitepress
```

## Running the Installer

After installing the package, run the installation command:

```bash
php artisan vitepress:install
```

This will:
- Publish the configuration file to `config/vitepress.php`
- Publish the pre-built documentation assets

## Verifying Installation

Visit your application at `/docs` to see the documentation.

```
https://your-app.test/docs
```

## Building Documentation

After customizing the source files, rebuild the documentation:

```bash
php artisan vitepress:build
```
