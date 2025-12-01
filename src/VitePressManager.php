<?php

declare(strict_types=1);

namespace LaravelReady\VitePress;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Gate;

class VitePressManager
{
    /**
     * The application instance.
     */
    protected Application $app;

    /**
     * Create a new VitePress manager instance.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Check if VitePress routing is enabled.
     */
    public function isEnabled(): bool
    {
        return (bool) config('vitepress.route.enabled', true);
    }

    /**
     * Get the documentation assets path.
     */
    public function getDocsPath(): string
    {
        $storage = config('vitepress.assets.storage', 'storage');
        $path = config('vitepress.assets.path', 'vitepress/docs');

        if ($storage === 'public') {
            return public_path($path);
        }

        return storage_path('app/'.$path);
    }

    /**
     * Get the route prefix path.
     */
    public function getRoutePath(): string
    {
        return config('vitepress.route.prefix', 'docs');
    }

    /**
     * Get the full URL to the documentation.
     */
    public function getRouteUrl(): string
    {
        $prefix = $this->getRoutePath();
        $domain = config('vitepress.route.domain');

        if ($domain) {
            return rtrim($domain, '/').'/'.$prefix;
        }

        return url($prefix);
    }

    /**
     * Get configuration value(s).
     *
     * @return mixed
     */
    public function getConfig(?string $key = null)
    {
        if ($key === null) {
            return config('vitepress');
        }

        return config("vitepress.{$key}");
    }

    /**
     * Check if authentication is enabled.
     */
    public function isAuthEnabled(): bool
    {
        return (bool) config('vitepress.auth.enabled', false);
    }

    /**
     * Check if the current user can access the documentation.
     */
    public function canAccess(): bool
    {
        if (! $this->isAuthEnabled()) {
            return true;
        }

        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Check custom gate
        if ($gate = config('vitepress.auth.gate')) {
            return Gate::allows($gate);
        }

        // Check roles
        $roles = config('vitepress.auth.roles', []);
        if (! empty($roles) && method_exists($user, 'hasAnyRole')) {
            if (! $user->hasAnyRole($roles)) {
                return false;
            }
        }

        // Check permissions
        $permissions = config('vitepress.auth.permissions', []);
        if (! empty($permissions) && method_exists($user, 'hasAnyPermission')) {
            if (! $user->hasAnyPermission($permissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the VitePress build source path.
     */
    public function getSourcePath(): string
    {
        return config('vitepress.build.source_path', base_path('resources/docs'));
    }

    /**
     * Get the VitePress build output path.
     */
    public function getOutputPath(): string
    {
        return $this->getDocsPath();
    }

    /**
     * Check if the documentation assets exist.
     */
    public function assetsExist(): bool
    {
        return is_dir($this->getDocsPath()) && file_exists($this->getDocsPath().'/index.html');
    }
}
