<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteNotification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        View::composer('*', function ($view) {
            $categories = Cache::remember('categories', 60, function () {
                return Category::with('translations', 'children')->get();
            });

            $siteNotification = Cache::remember('active_site_notification', 60, function () {
                return SiteNotification::where('is_active', true)->latest()->first();
            });

            $view->with([
                'categories' => $categories,
                'siteNotification' => $siteNotification
            ]);
        });
    }
}
