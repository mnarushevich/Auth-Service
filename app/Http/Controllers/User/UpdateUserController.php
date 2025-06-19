<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

final class UpdateUserController extends Controller
{
    /**
     * @OA\Patch(
     *     path="/users",
     *     summary="Update user",
     *     description="Update user",
     *     operationId="usersUpdate",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(ref="#/components/schemas/UpdateUser")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Not Found.")
     * )
     */
    public function __invoke(UpdateUserRequest $request, User $user): UserResource
    {
        Gate::authorize('update', $user);
        $user->update($request->validated());

        if ($request->has('address.country')) {
            $user->address()->update(
                ['country' => $request->input('address.country')]
            );
        }

        Cache::forget(sprintf('user-%s', $user->uuid));

        return new UserResource($user);
    }
}
