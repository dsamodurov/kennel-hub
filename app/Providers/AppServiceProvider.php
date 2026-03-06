<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\News;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\View;

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
        Inertia::share('appName', config('app.name', 'Kennel HUB'));

        View::composer('default.*', function ($view) {
            $view->with('publicPages', Page::published()->orderBy('title')->get(['title', 'slug']));
            $view->with('latestNews', News::published()->latest('published_at')->take(3)->get());
        });
    }
}
