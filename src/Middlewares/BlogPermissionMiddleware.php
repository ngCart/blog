<?php

namespace IFrankSmith\Blogman\Middlewares;

use Closure;
use Illuminate\Http\Request;

class BlogPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        if (app('auth')->guard($guard)->guest()) {
            abort(401, 'Unauthorized');
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (app('auth')->guard($guard)->user()->hasBlogPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Permission denied');
    }

}
