<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        $user = session('user');

        if (!$user || $user['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized');
        }

        return $next($request);
    }
}
