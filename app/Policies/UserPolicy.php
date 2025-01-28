<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function delete(User $authUser, User $user): bool
    {
        return $authUser->role === UserRole::ADMIN || $authUser->uuid === $user->uuid;
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->role === UserRole::ADMIN->value || $authUser->uuid === $user->uuid;
    }
}
