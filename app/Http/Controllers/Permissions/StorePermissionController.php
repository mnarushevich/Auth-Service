<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class StorePermissionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/permissions",
     *     summary="Create new permission",
     *     description="Create new permission",
     *     operationId="permissionsCreate",
     *     tags={"Permissions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(ref="#/components/schemas/CreatePermission")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function __invoke(StorePermissionRequest $request): JsonResponse|PermissionResource
    {
        $permissionName = $request->input('name');

        if (Permission::query()->where('name', $permissionName)->exists()) {
            throw new BadRequestHttpException(sprintf('Permission with name `%s` already exist', $permissionName));
        }

        $permission = new Permission;
        $permission->name = $permissionName;
        $permission->guard_name = $request->input('guard_name');
        $permission->save();

        return new PermissionResource($permission);
    }
}
