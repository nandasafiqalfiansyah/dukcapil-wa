<?php

namespace App\Providers;

use App\Models\AutoReplyConfig;
use App\Models\CsTrainingData;
use App\Observers\AutoReplyConfigObserver;
use App\Observers\CsTrainingDataObserver;
use Illuminate\Support\Facades\URL;
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
        // Force HTTPS URLs in production when behind a proxy
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Register observers for auto cache clearing
        CsTrainingData::observe(CsTrainingDataObserver::class);
        AutoReplyConfig::observe(AutoReplyConfigObserver::class);
    }
}
