<?php

namespace App\Policies;

use App\Constants;
use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Общее разрешение Контакт-центра
     * @param User $user
     * @return Response
     */
    public static function contactCenterChat (User $user): Response
    {
        //Проверяем что пользователь может работать с Контакт-центром
        if (!$user->hasAnyRule(['contactCenter_admin', 'contactCenter_user'])) {
            return Response::deny('Требуется роль, дающая доступ к модулю Контакт-центра', 403);
        }

        //Авторизация пройдена
        return Response::allow();
    }
}
