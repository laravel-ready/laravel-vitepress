# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [GitHub](https://github.com/laravel-ready/laravel-vitepress).

## Pull Requests

- **[PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)** - The easiest way to apply the conventions is to use [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).
- **Add tests!** - Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Consider our release cycle** - We try to follow [SemVer v2.0.0](https://semver.org/). Randomly breaking public APIs is not an option.
- **Create feature branches** - Don't ask us to pull from your main branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](https://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

## Running Tests

```bash
composer test
```

## Code Style

We use [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to maintain code style consistency.

```bash
composer format
```

## Static Analysis

We use [PHPStan](https://phpstan.org/) for static analysis.

```bash
composer analyse
```

## Development Setup

1. Fork the repository
2. Clone your fork
3. Install dependencies: `composer install`
4. Run tests: `composer test`
5. Make your changes
6. Run tests again
7. Submit a pull request

## Building Documentation

To build the VitePress documentation locally:

```bash
cd stubs/docs
npm install
npm run dev  # Development server
npm run build  # Production build
```

**Happy coding**!
