<?php

declare(strict_types=1);

namespace LaravelReady\VitePress;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelReady\VitePress\Commands\BuildCommand;
use LaravelReady\VitePress\Http\Middleware\VitePressAuth;
use LaravelReady\VitePress\Commands\InstallCommand;
use LaravelReady\VitePress\Commands\PublishCommand;

class VitePressServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/vitepress.php',
            'vitepress'
        );

        $this->app->singleton('vitepress', function ($app) {
            return new VitePressManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerCommands();
        $this->registerViews();
    }

    /**
     * Register the package's middleware.
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app->make('router');
        $router->aliasMiddleware('vitepress.auth', VitePressAuth::class);
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                __DIR__.'/../config/vitepress.php' => config_path('vitepress.php'),
            ], 'vitepress-config');

            // Publish pre-built assets to the configured location
            $this->publishes([
                __DIR__.'/../storage/app/vitepress/docs' => $this->getAssetsPublishPath(),
            ], 'vitepress-assets');

            // Publish VitePress source stubs
            $this->publishes([
                __DIR__.'/../stubs/docs' => resource_path('docs'),
            ], 'vitepress-stubs');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/vitepress'),
            ], 'vitepress-views');
        }
    }

    /**
     * Get the path where assets should be published.
     */
    protected function getAssetsPublishPath(): string
    {
        $storage = config('vitepress.assets.storage', 'storage');
        $path = config('vitepress.assets.path', 'vitepress/docs');

        if ($storage === 'public') {
            return public_path($path);
        }

        return storage_path('app/'.$path);
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        if (! config('vitepress.route.enabled', true)) {
            return;
        }

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/vitepress.php');
        });
    }

    /**
     * Get the route group configuration array.
     */
    protected function routeConfiguration(): array
    {
        $config = [
            'prefix' => config('vitepress.route.prefix', 'docs'),
            'as' => config('vitepress.route.name', 'vitepress').'.',
            'middleware' => config('vitepress.route.middleware', ['web']),
        ];

        if ($domain = config('vitepress.route.domain')) {
            $config['domain'] = $domain;
        }

        // Always add vitepress.auth middleware (it checks config internally)
        // This allows config to be changed at runtime (useful for testing)
        $config['middleware'] = [
            ...$config['middleware'],
            'vitepress.auth',
        ];

        return $config;
    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                PublishCommand::class,
                BuildCommand::class,
            ]);
        }
    }

    /**
     * Register the package's views.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'vitepress');
    }
}
