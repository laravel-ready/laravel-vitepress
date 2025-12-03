<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class VitePressAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('vitepress.auth.enabled', false)) {
            return $next($request);
        }

        $user = $request->user();

        if (! $user) {
            return $this->unauthorized($request);
        }

        // Check custom gate
        if ($gate = config('vitepress.auth.gate')) {
            if (Gate::denies($gate)) {
                return $this->unauthorized($request);
            }
        }

        // Check roles
        $roles = config('vitepress.auth.roles', []);
        if (! empty($roles) && method_exists($user, 'hasAnyRole')) {
            if (! $user->hasAnyRole($roles)) {
                return $this->unauthorized($request);
            }
        }

        // Check permissions
        $permissions = config('vitepress.auth.permissions', []);
        if (! empty($permissions) && method_exists($user, 'hasAnyPermission')) {
            if (! $user->hasAnyPermission($permissions)) {
                return $this->unauthorized($request);
            }
        }

        return $next($request);
    }

    /**
     * Handle unauthorized access.
     */
    protected function unauthorized(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthorized access to documentation.',
            ], 403);
        }

        $redirect = config('vitepress.auth.redirect_unauthorized_to', '/login');

        return redirect($redirect)->with('error', 'You do not have permission to access the documentation.');
    }
}
