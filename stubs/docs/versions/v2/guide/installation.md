# Installation (v2)

## Requirements

- PHP 8.1+
- Laravel 10.x or 11.x
- Node.js 18+ (for building documentation)

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

## Publishing VitePress Source

If you want to customize the documentation source files:

```bash
php artisan vitepress:publish --stubs
```

This will publish the VitePress source files to `resources/docs/`.

## Building Documentation

After customizing the source files, rebuild the documentation:

```bash
php artisan vitepress:build
```

Or use npm directly:

```bash
cd resources/docs
npm install
npm run docs:build
```
