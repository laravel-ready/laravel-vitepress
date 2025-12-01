<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Tests;

use Illuminate\Support\Facades\File;
use LaravelReady\VitePress\VitePressServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTestDocumentation();
    }

    protected function getPackageProviders($app): array
    {
        return [
            VitePressServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('vitepress.route.enabled', true);
        $app['config']->set('vitepress.assets.storage', 'storage');
        $app['config']->set('vitepress.assets.path', 'vitepress/docs');
    }

    protected function setUpTestDocumentation(): void
    {
        $docsPath = storage_path('app/vitepress/docs');

        File::ensureDirectoryExists($docsPath);
        File::ensureDirectoryExists("{$docsPath}/guide");
        File::ensureDirectoryExists("{$docsPath}/assets");

        // Create test files
        File::put(
            "{$docsPath}/index.html",
            '<!DOCTYPE html><html><head><title>Test Docs</title></head><body><h1>Test Documentation</h1></body></html>'
        );

        File::put(
            "{$docsPath}/assets/style.css",
            'body { font-family: sans-serif; }'
        );

        File::put(
            "{$docsPath}/guide/getting-started.html",
            '<!DOCTYPE html><html><head><title>Getting Started</title></head><body><h1>Getting Started</h1></body></html>'
        );
    }

    protected function tearDown(): void
    {
        $docsPath = storage_path('app/vitepress');

        if (File::exists($docsPath)) {
            File::deleteDirectory($docsPath);
        }

        parent::tearDown();
    }
}
