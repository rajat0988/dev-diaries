<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        //To add htttps in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Optimize: Disable lazy loading in development to catch N+1 issues
        // Model::preventLazyLoading(!app()->isProduction());

        // Optimize: Set pagination default per page
        \Illuminate\Pagination\Paginator::defaultView('pagination::tailwind');

        // Clear tag cache when questions are created/updated/deleted
        \App\Models\Question::created(function () {
            cache()->forget('tag_counts');
        });

        \App\Models\Question::updated(function () {
            cache()->forget('tag_counts');
        });

        \App\Models\Question::deleted(function () {
            cache()->forget('tag_counts');
        });
    }
}
