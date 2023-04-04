<?php

namespace App\Providers;

use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['broadcasting']]);  //Задаём группу посредников. Она в целом копирует группу 'web'

        require base_path('routes/channels.php');   //В этом файле заданым слушатели

        DriverManager::loadDriver(TelegramDriver::class);
    }
}
