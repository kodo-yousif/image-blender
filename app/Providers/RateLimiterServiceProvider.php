<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('login', function ($request) {
            $username = (string) $request->input('username');

            return [
                Limit::perMinute(5)->by($username.$request->ip())
                    ->response(function () {
                        return response()->json([
                            'message' => 'Too many login attempts. Please try again in a minute.'
                        ], 429);
                    }),
                Limit::perHour(15)->by($username.$request->ip()),
            ];
        });
    }
}
