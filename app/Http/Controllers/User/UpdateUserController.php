<?php

namespace App\Http\Controllers\User;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
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
    public function __invoke(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->role = $request->input('role') ?? UserRole::USER->value;
        $user->save();

        if ($request->has('address.country')) {
            $user->address()->update(
                ['country' => $request->input('address.country')]
            );
        }

        return new UserResource($user);
    }
}
