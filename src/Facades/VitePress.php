<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isEnabled()
 * @method static string getDocsPath()
 * @method static string getRoutePath()
 * @method static string getRouteUrl()
 * @method static array getConfig(?string $key = null)
 * @method static bool isAuthEnabled()
 * @method static bool canAccess()
 *
 * @see \LaravelReady\VitePress\VitePressManager
 */
class VitePress extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'vitepress';
    }
}
