<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionsEnum: string
{
    case USERS_CREATE = 'create users';
    case USERS_EDIT = 'edit users';
    case USERS_DELETE = 'delete users';
    case USERS_VIEW = 'view users';

    public static function all(): array
    {
        return [
            PermissionsEnum::USERS_CREATE,
            PermissionsEnum::USERS_EDIT,
            PermissionsEnum::USERS_DELETE,
            PermissionsEnum::USERS_VIEW,
        ];
    }
}
