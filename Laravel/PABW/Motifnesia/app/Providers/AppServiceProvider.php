<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\Produk;
use App\Models\ProductReturn;
use App\Observers\OrderObserver;
use App\Observers\ReviewObserver;
use App\Observers\ProductObserver;
use App\Observers\ProductReturnObserver;

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
        // Register observers untuk auto-generate notifications
        Order::observe(OrderObserver::class);
        OrderReview::observe(ReviewObserver::class);
        Produk::observe(ProductObserver::class);
        ProductReturn::observe(ProductReturnObserver::class);
    }
}
