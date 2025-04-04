<?php

declare(strict_types=1);

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            RolesEnum::ADMIN => 'Admins',
            RolesEnum::USER => 'Users',
        };
    }
}
