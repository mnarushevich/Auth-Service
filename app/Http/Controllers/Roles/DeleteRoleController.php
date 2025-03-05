<?php

declare(strict_types=1);

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DeleteRoleController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/roles/{name}",
     *     summary="Delete role",
     *     description="Delete role",
     *     operationId="rolesDelete",
     *     tags={"Roles"},
     *
     *     @OA\RequestBody(
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(ref="#/components/schemas/DeleteRole")
     *          )
     *      ),
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Not Found.")
     * )
     */
    public function __invoke(DeleteRoleRequest $request): JsonResponse
    {
        $name = $request->input('name');
        $guardName = $request->input('guard_name');
        $role = Role::query()->where('name', $name)->where('guard_name', $guardName)->first();
        if ($role === null) {
            throw new NotFoundHttpException(
                sprintf('Role with name `%s` and guard name `%s` not found.', $name, $guardName),
            );
        }

        $role->delete();

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Role with name `%s` and guard name `%s` was deleted.', $name, $guardName),
        ]);
    }
}
