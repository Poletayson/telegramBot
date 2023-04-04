<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Api\Auth2Controller;
use App\Policies\AdminPanelPolicy;
use App\Policies\ChatPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Привязыаем контроллер как одиночку к контейнеру служб
        $this->app->singleton(Auth2Controller::class, function ($app) {
            return new Auth2Controller();
        });

        //Регистрируем провайдера
        Auth::provider('auth2API', function ($app, array $config) {
            // Возвращаем экземпляр `Illuminate\Contracts\Auth\UserProvider` ...
            return new Auth2UserProvider();
        });

        //Регистрируем методы политик как шлюзы
        Gate::define('AdminPanelPolicy-adminPanel', [AdminPanelPolicy::class, 'adminPanel']);
        Gate::define('ChatPolicy-contactCenterChat', [ChatPolicy::class, 'contactCenterChat']);
    }
}
