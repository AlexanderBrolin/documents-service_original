<?php

namespace App\Constants;

class Roles
{
    public const DEVELOPER = 'Разработчик';
    public const DEVELOPER_ALIAS = 'developer';

    public const ADMINISTRATOR = 'Администратор';
    public const ADMINISTRATOR_ALIAS = 'administrator';

    public const MANAGER = 'Руководитель';
    public const MANAGER_ALIAS = 'manager';

    public const WORKER = 'Работник';
    public const WORKER_ALIAS = 'worker';

    public static function getAllRoles(): array {
        return [
            'developer' => [
                'name' => 'Разработчик',
                'alias' => 'developer',
            ],
            'administrator' => [
                'name' => 'Администратор',
                'alias' => 'administrator',
            ],
            'manager' => [
                'name' => 'Руководитель',
                'alias' => 'manager',
            ],
            'worker' => [
                'name' => 'Сотрудник',
                'alias' => 'worker'
            ]
        ];
    }
}
