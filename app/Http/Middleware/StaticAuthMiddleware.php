<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
