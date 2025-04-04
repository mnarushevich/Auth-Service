<?php

declare(strict_types=1);

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class StoreRoleController extends Controller
{
    /**
     * @OA\Post(
     *     path="/roles",
     *     summary="Create new role",
     *     description="Create new role",
     *     operationId="rolesCreate",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(ref="#/components/schemas/CreateRole")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function __invoke(StoreRoleRequest $request): RoleResource
    {
        $roleName = $request->input('name');

        if (Role::query()->where('name', $roleName)->exists()) {
            throw new BadRequestHttpException("Role with name `$roleName` already exist");
        }

        $role = new Role;
        $role->name = $roleName;
        $role->guard_name = $request->input('guard_name');
        $role->save();

        return new RoleResource($role);
    }
}
