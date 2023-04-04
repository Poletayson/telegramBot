<?php

namespace App\Providers\BotMan;

use BotMan\BotMan\BotManServiceProvider;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\Providers\TelegramServiceProvider as ServiceProvider;

class DriverServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * The drivers that should be loaded to
     * use with BotMan
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * @return void
     */
    public function boot()
    {
//        parent::boot();

//        foreach ($this->drivers as $driver) {
//            DriverManager::loadDriver($driver);
//        }
//        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
        //$botman = BotManFactory::create(config('botman.telegram'));
    }
}
