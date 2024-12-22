<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Log;

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
