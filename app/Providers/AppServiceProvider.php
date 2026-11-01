<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.store', function ($view) {
            $defaults = [
                'announcement' => 'Complimentary delivery across India on orders over ₹15,000',
                'footer_story' => 'Furniture for real homes and everyday moments—comfortable, considered and made to stay with you.',
                'footer_email' => 'hello@msfurniture.in',
                'footer_phone' => '+91 00000 00000',
                'footer_address' => 'India · Nationwide delivery',
                'instagram_url' => '#',
                'facebook_url' => '#',
                'pinterest_url' => '#',
            ];

            if (Schema::hasTable('settings')) {
                foreach ($defaults as $key => $default) {
                    $defaults[$key] = Setting::valueFor($key, $default);
                }
            }

            $view->with('storeSettings', $defaults);
        });
    }
}
