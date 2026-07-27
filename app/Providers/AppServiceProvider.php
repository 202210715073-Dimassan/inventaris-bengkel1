<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                try {
                    $lowStockCount = \App\Models\Product::whereColumn('stock', '<=', 'rop_value')->count();
                    $view->with('lowStockCount', $lowStockCount);
                } catch (\Exception $e) {
                    $view->with('lowStockCount', 0);
                }
            } else {
                $view->with('lowStockCount', 0);
            }
        });
    }
}
