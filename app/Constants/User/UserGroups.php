<?php

namespace App\Constants\User;

/**
 *  Группы пользователей. Группа пользователя определяет некоторые права пользоваеля в РМИС
 */
class UserGroups
{
    /**
     * Старшие администраторы
     */
    public const MAJOR_ADMINISTRATORS = 0;

    /**
     * Администраторы
     */
    public const ADMINISTRATORS = 1;

    /**
     * Старшие регистраторы
     */
    public const MAJOR_REGISTRATORS = 2;

    /**
     * Регистраторы
     */
    public const REGISTRATORS = 3;

    /**
     * Статистики
     */
    public const STATISTICS = 4;

    /**
     * Врачи
     */
    public const DOCTORS = 5;

    /**
     * Клиенты
     */
    public const CLIENTS = 6;

    /**
     * Регистратор-администратор
     */
    public const REGISTRATOR_ADMINISTRATOR = 7;

    /**
     * Менеджеры
     */
    public const MANAGERS = 8;

    /**
     * Лаборанты
     */
    public const LABORANTS = 9;

    /**
     * Оператор call-центра
     */
    public const OPERATOR = 10;

    /**
     * Приложения
     */
    public const APPLICATIONS = 11;

    /**
     * Сотрудник ОУ
     */
    public const EMPLOYEE_OU = 12;

    /**
     * Сотрудник ТФОМС
     */
    public const EMPLOYEE_TFOMS = 13;

    /**
     * Сотрудник СМО
     */
    public const EMPLOYEE_SMO = 14;
}
