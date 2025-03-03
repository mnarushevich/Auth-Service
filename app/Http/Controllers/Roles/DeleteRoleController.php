<?php

declare(strict_types=1);

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     *     @OA\Parameter(
     *          name="name",
     *          description="Role valid name",
     *          in = "path",
     *          required=true,
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Not Found.")
     * )
     */
    public function __invoke(string $name): JsonResponse
    {
        $role = Role::query()->where('name', $name)->first();
        if ($role === null) {
            return response()->json([
                'status' => 'error',
                'message' => sprintf('Role with name %s not found.', $name),
            ], Response::HTTP_NOT_FOUND);
        }

        $role->delete();

        return response()->json([
            'status' => 'ok',
            'message' => sprintf('Roles with name %s was deleted.', $name),
        ]);
    }
}
