<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Log;
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
        // \DB::listen(function ($query) {
        //     \Log::info($query->sql, $query->bindings, $query->time);
        // });
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        DB::listen(function ($query) {
            if (str_contains($query->sql, 'insert into `sensors_data`')) {
                Log::info('Insert query detected on sensors_data table:', [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ]);
            }
        });

    }
}
