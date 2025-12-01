# Changelog

All notable changes to `laravel-vitepress` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
