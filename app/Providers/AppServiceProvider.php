<?php

namespace App\Providers;

use App\Models\DataItem;
use App\Observers\DataItemObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        DataItem::observe(DataItemObserver::class);
        Paginator::useBootstrapFive();
    }
}
