<?php

namespace App\Http\Controllers\Auth;

use App\Api\Auth2Controller;
use App\Models\Auth\User;
use Illuminate\Container\Container;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\SessionManager;

/**
 * Контроллер для работы с пользователем
 */
class UserController extends \App\Http\Controllers\Controller
{

    /**
     * Получить аутентифицированного пользователя
     * @return User|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function getAuthentifiedUser () {
        $user = null;

        $session = session();
//        $allData = $session->all();
        if ($session->has('user')) {
            $user = unserialize($session->get('user'));
        } else {
            $user = (new Auth2Controller())->requestUser();
        }
        return $user;
    }

    /**
     * Поместить пользователя в сессию. Возможно обновление полей пользователя таким образом
     * @param User $user Аутентифицированный пользователь
     * @return User|mixed|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function putUserToSession (User $user) {
        //Запоминаем сериализованного пользователя в сессии
        session()->put('user', serialize($user));
    }
}
