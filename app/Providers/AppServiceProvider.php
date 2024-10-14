<?php

namespace App\Providers;

use Illuminate\Http\Request;
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
        //
        function arrayToLower($data)
        {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = arrayToLower($value);
                } else if (is_string($value)) {
                    $data[$key] = strtolower($value);
                }
            }
            return $data;
        }

        // Defining a macro for the Request class
        Request::macro('allLower', function () {
            return arrayToLower($this->all());
        });
    }
}
