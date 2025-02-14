<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use App\Models\Setting\Setting;
use App\Services\MailService;

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
        try {
            $setting = Setting::firstOrFail();
            date_default_timezone_set($setting->timezone);
            view()->share('setting', $setting);
            (new MailService())->loadMailConfig();

        } catch (\Exception $e) {
            $setting = [];
            date_default_timezone_set(config('app.timezone'));
        }
    }
}
