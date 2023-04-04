<?php

namespace App\Policies;

use App\Constants;
use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class AdminPanelPolicy
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
     * Разрешён ли пользователю доступ к панели администратора
     * @param User $user
     * @return Response
     */
    public static function adminPanel (User $user): Response
    {
        //Проверяем что пользователь может работать с Контакт-центром
        if (!$user->hasAnyRule(['contactCenter_admin', 'contactCenter_user'])) {
            return Response::deny('Требуется роль, дающая доступ к модулю Контакт-центра', 403);
        }

        //Пользователь должен быть Администратором или Старшим администратором
        if ($user->getGroupId() != Constants\User\UserGroups::MAJOR_ADMINISTRATORS
            && $user->getGroupId() != Constants\User\UserGroups::ADMINISTRATORS) {
            return Response::deny('Раздел доступен только администраторам', 403);
        }

        //Авторизация пройдена
        return Response::allow();
    }
}
