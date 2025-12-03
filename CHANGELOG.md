# Changelog

All notable changes to `laravel-vitepress` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.2] - 2025-12-03

### Added

- Registered `VitePressAuth` middleware with alias `vitepress.auth` in service provider
- Comprehensive unit tests for `VitePressAuth` middleware (14 new tests)

### Changed

- Middleware is now always applied to routes (checks config internally for runtime flexibility)
- Authorization logic consolidated in middleware, removed duplicate code from controller

### Removed

- Removed inline authorization closure from `VitePressController` constructor
- Removed `authorize()` and `handleUnauthorized()` methods from controller

## [1.2.1] - 2025-12-02

### Added

- Custom 404 page view with improved layout and code block styling
- PHPStan static analysis workflow and dependencies
- GitHub Actions workflow for automated testing
- Documentation build verification to GitHub Actions workflow
- PHP/Laravel support matrix documentation

### Changed

- Renamed `userConfig` to `docsConfig` for better semantic clarity
- Improved markdown formatting consistency in documentation files
- Updated Pest dependencies

### Removed

- Removed redundant `.env` file loading from VitePress config
- Removed package.json in favor of PNPM workspace management

## [1.0.0] - 2025-12-01

### Added

- Initial release
- VitePress documentation serving through Laravel
- Authentication support with Laravel's built-in auth
- Role-based access control (compatible with Spatie Laravel Permission)
- Permission-based access control
- Custom gate support
- Configurable routing with prefix and domain support
- Cache control headers
- Path traversal protection
- SPA fallback routing
- CORS support (optional)
- Custom headers support
- Artisan commands: `vitepress:install`, `vitepress:publish`, `vitepress:build`
- VitePress facade for easy access
- Comprehensive test suite
- GitHub Actions workflows for testing and documentation building
