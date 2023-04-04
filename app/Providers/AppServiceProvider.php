<?php

namespace App\Providers;

use App\Http\Controllers\Auth\UserController;
use App\Models\Auth\User;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->app->bind(\DateTimeZone::class, function ($app) {
//            return new \DateTimeZone(config('app.timezone'));
//        });
//        //Внедрение класса аутентифицированного пользователя
//        $this->app->bind(User::class, function ($app) {
//            UserController::getAuthentifiedUser();
//        });


//        URL::forceScheme('https');
    }
}
