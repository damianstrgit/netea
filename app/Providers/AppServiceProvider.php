<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Validators\RestValidator;

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
        \Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new RestValidator($translator, $data, $rules, $messages);
        });
    }
}
