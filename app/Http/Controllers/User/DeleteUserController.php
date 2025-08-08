<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

final class DeleteUserController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/users/{uuid}",
     *     summary="Delete user",
     *     description="Delete user",
     *     operationId="usersDelete",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *          name="uuid",
     *          description="User valid uuid",
     *          in = "path",
     *          required=true,
     *
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Not Found.")
     * )
     */
    public function __invoke(User $user): JsonResponse
    {
        Gate::authorize('delete', $user);
        $user->delete();
        Cache::forget(sprintf('user-%s', $user->uuid));

        return response()->json([
            'status' => 'ok',
            'message' => sprintf('User with uuid %s was deleted.', $user->uuid),
        ]);
    }
}
