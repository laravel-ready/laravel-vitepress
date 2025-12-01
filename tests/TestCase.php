<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Tests;

use LaravelReady\VitePress\VitePressServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTestDocumentation();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            VitePressServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('vitepress.route.enabled', true);
        $app['config']->set('vitepress.assets.path', 'vendor/laravel-vitepress/docs');
    }

    /**
     * Set up test documentation files.
     */
    protected function setUpTestDocumentation(): void
    {
        $docsPath = public_path('vendor/laravel-vitepress/docs');

        if (! is_dir($docsPath)) {
            mkdir($docsPath, 0755, true);
        }

        // Create a simple index.html for testing
        file_put_contents(
            $docsPath.'/index.html',
            '<!DOCTYPE html><html><head><title>Test Docs</title></head><body><h1>Test Documentation</h1></body></html>'
        );

        // Create a test CSS file
        file_put_contents(
            $docsPath.'/style.css',
            'body { font-family: sans-serif; }'
        );

        // Create a nested page
        $guidePath = $docsPath.'/guide';
        if (! is_dir($guidePath)) {
            mkdir($guidePath, 0755, true);
        }

        file_put_contents(
            $guidePath.'/getting-started.html',
            '<!DOCTYPE html><html><head><title>Getting Started</title></head><body><h1>Getting Started</h1></body></html>'
        );
    }

    /**
     * Clean up test documentation files.
     */
    protected function tearDown(): void
    {
        $docsPath = public_path('vendor/laravel-vitepress');

        if (is_dir($docsPath)) {
            $this->deleteDirectory($docsPath);
        }

        parent::tearDown();
    }

    /**
     * Delete a directory recursively.
     */
    protected function deleteDirectory(string $dir): bool
    {
        if (! is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir.'/'.$file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dir);
    }

    /**
     * Create a mock user for testing.
     */
    protected function createMockUser(array $capabilities = []): object
    {
        return new class($capabilities)
        {
            private array $capabilities;

            public function __construct(array $capabilities)
            {
                $this->capabilities = $capabilities;
            }

            public function hasAnyRole(array $roles): bool
            {
                return ! empty(array_intersect($this->capabilities['roles'] ?? [], $roles));
            }

            public function hasAnyPermission(array $permissions): bool
            {
                return ! empty(array_intersect($this->capabilities['permissions'] ?? [], $permissions));
            }
        };
    }
}
