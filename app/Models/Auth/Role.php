<?php

namespace App\Models\Auth;

/**
 * Роль пользователя, определяющая набор правил
 */
class Role
{
    /**
     * @var string Идентификатор роли, это должнен быть валидный UUID
     */
    private string $id;

    /**
     * @var string Название роли
     */
    private string $name;


}
