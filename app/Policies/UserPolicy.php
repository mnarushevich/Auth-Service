<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RolesEnum;
use App\Models\User;

class UserPolicy
{
    public function delete(User $authUser, User $user): bool
    {
        if ($authUser->hasRole(RolesEnum::ADMIN->value)) {
            return true;
        }

        return $authUser->uuid === $user->uuid;
    }

    public function update(User $authUser, User $user): bool
    {
        if ($authUser->hasRole(RolesEnum::ADMIN->value)) {
            return true;
        }

        return $authUser->uuid === $user->uuid;
    }
}
