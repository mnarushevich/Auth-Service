<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeletePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DeletePermissionController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/permissions/{name}",
     *     summary="Delete permission",
     *     description="Delete permission",
     *     operationId="permissionsDelete",
     *     tags={"Permissions"},
     *
     *     @OA\RequestBody(
     *
     *           @OA\MediaType(
     *               mediaType="application/json",
     *
     *               @OA\Schema(ref="#/components/schemas/DeletePermission")
     *           )
     *       ),
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Not Found.")
     * )
     */
    public function __invoke(DeletePermissionRequest $request): JsonResponse
    {
        $name = $request->input('name');
        $guardName = $request->input('guard_name');
        $permission = Permission::query()->where('name', $name)->where('guard_name', $guardName)->first();
        if ($permission === null) {
            throw new NotFoundHttpException(
                sprintf('Permission with name `%s` and guard name `%s` not found.', $name, $guardName),
            );
        }

        $permission->delete();

        return response()->json([
            'status' => 'ok',
            'message' => sprintf('Permission with name `%s` and guard name `%s` was deleted.', $name, $guardName),
        ]);
    }
}
