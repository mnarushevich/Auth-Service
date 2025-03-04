<?php

declare(strict_types=1);

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
    public function __invoke(StoreRoleRequest $request): JsonResponse|RoleResource
    {
        $roleName = $request->input('name');

        if (Role::query()->where('name', $roleName)->exists()) {
            return response()->json(
                ['error' => "Role with name `$roleName` already exist"],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $role = new Role;
        $role->name = $roleName;
        $role->guard_name = $request->input('guard_name');
        $role->save();

        return new RoleResource($role);
    }
}
