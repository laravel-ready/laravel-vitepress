<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Documentation Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how the documentation routes should be registered.
    |
    */
    'route' => [
        'enabled' => env('VITEPRESS_ENABLED', true),
        'prefix' => env('VITEPRESS_ROUTE_PREFIX', 'docs'),
        'domain' => env('VITEPRESS_DOMAIN', null),
        'name' => env('VITEPRESS_ROUTE_NAME', 'vitepress'),
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication & Authorization
    |--------------------------------------------------------------------------
    |
    | Control access to your documentation with Laravel's authentication
    | and authorization features.
    |
    */
    'auth' => [
        'enabled' => env('VITEPRESS_AUTH_ENABLED', false),
        'middleware' => ['auth'],

        // Role-based access (requires spatie/laravel-permission or similar)
        'roles' => env('VITEPRESS_ALLOWED_ROLES', null)
            ? explode(',', env('VITEPRESS_ALLOWED_ROLES'))
            : [],

        // Permission-based access
        'permissions' => env('VITEPRESS_ALLOWED_PERMISSIONS', null)
            ? explode(',', env('VITEPRESS_ALLOWED_PERMISSIONS'))
            : [],

        // Custom gate
        'gate' => env('VITEPRESS_GATE', null),

        // Redirect unauthorized users
        'redirect_unauthorized_to' => env('VITEPRESS_UNAUTHORIZED_REDIRECT', '/login'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where the built VitePress assets are stored and served from.
    | By default, assets are stored in storage/ to prevent direct access
    | and ensure authentication/authorization is enforced.
    |
    | Set 'storage' => 'public' if you want public docs without auth.
    |
    */
    'assets' => [
        // Storage location: 'storage' (protected) or 'public' (directly accessible)
        'storage' => env('VITEPRESS_STORAGE', 'storage'),

        // Path relative to the storage location
        'path' => env('VITEPRESS_ASSETS_PATH', 'vitepress/docs'),

        // Cache control headers
        'cache' => [
            'enabled' => env('VITEPRESS_CACHE_ENABLED', true),
            'max_age' => env('VITEPRESS_CACHE_MAX_AGE', 3600),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | VitePress Build Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for building VitePress documentation.
    |
    */
    'build' => [
        'source_path' => base_path('resources/docs'),
        'base_url' => env('VITEPRESS_BASE_URL', '/docs/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Options
    |--------------------------------------------------------------------------
    */
    'options' => [
        // Enable/disable index fallback for SPA routing
        'spa_fallback' => true,

        /**
         * Custom 404 view (set to null to use abort(404))
         *
         * Default: 'vitepress::errors.docs-404'
         * Set to null to use abort(404) instead
         */
        'custom_404' => env('VITEPRESS_CUSTOM_404', 'vitepress::errors.docs-404'),

        // Enable CORS for assets
        'cors_enabled' => false,

        // Additional headers for responses
        'headers' => [],
    ],
];
