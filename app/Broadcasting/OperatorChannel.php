<?php

namespace App\Broadcasting;

use App\Models\Auth\User;

/**
 * Канал для получения оператором событий
 */
class OperatorChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Подтвердить доступ пользователя к каналу.
     *
     * @param User $user
     * @param int $operatorId
     * @return array|bool
     */
    public function join(User $user, int $operatorId): bool|array
    {
        return $user->getUserId() == $operatorId;
    }

}
