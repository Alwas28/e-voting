<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadUserPermissions
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $request->user()->loadMissing('roles.permissions');
        }

        return $next($request);
    }
}
