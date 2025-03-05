<?php

declare(strict_types=1);

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RemoveUserRoleController extends Controller
{
    public function __invoke(User $user, AssignUserRoleRequest $request)
    {
        $roleName = $request->input('role_name');

        if (! Role::query()->where('name', $roleName)->exists()) {
            throw new BadRequestHttpException("Role with name `$roleName` does not exist");
        }

        if (! $user->hasRole($roleName)) {
            throw new BadRequestHttpException("User does not have role `$roleName`");
        }

        $user->removeRole($roleName);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Role removed successfully',
        ]);
    }
}
