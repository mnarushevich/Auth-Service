<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

final class ShowUserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users/{uuid}",
     *     summary="Update user",
     *     description="Update user",
     *     operationId="usersShow",
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
    public function __invoke(string $uuid): JsonResponse
    {
        $user = Cache::remember(sprintf('user-%s', $uuid), config('cache.default_ttl'),
            fn (): UserResource => new UserResource(
                User::query()
                    ->with(['address', 'roles', 'permissions'])
                    ->where('uuid', $uuid)
                    ->firstOrFail()
            ));

        return response()->json(data: ['data' => $user]);
    }
}
