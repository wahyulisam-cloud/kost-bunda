<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        if (app()->environment('production')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');

            if (request()->header('x-forwarded-proto') === 'https') {
                request()->server->set('HTTPS', 'on');
            }
        }
    }
}