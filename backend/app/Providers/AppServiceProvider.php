<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('registration', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('account-login', function (Request $request): array {
            $account = $request->input('tai_khoan');
            $key = is_string($account) ? strtolower(trim($account)) : '';

            return [Limit::perMinute(20)->by('ip:'.$request->ip()),
                Limit::perMinute(5)->by('account:'.hash('sha256', $key).'|'.$request->ip())];
        });
        RateLimiter::for('otp-send', function (Request $request): array {
            $account = $request->input('tai_khoan');
            $key = $request->user()?->id ?? (is_string($account) ? strtolower(trim($account)) : '');

            return [Limit::perMinute(5)->by('ip:'.$request->ip()),
                Limit::perMinute(1)->by('contact:'.hash('sha256', (string) $key))];
        });
        RateLimiter::for('otp-check', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
