<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class StoreUserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Create new user",
     *     description="Create new user",
     *     operationId="usersCreate",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(ref="#/components/schemas/CreateUser")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function __invoke(StoreUserRequest $request): UserResource
    {
        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->role = $request->input('role') ?? UserRole::USER->value;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->address()->create([
            'country' => $request->input('address.country'),
        ]);
        $user->refresh()->load('address');

        return new UserResource($user);
    }
}
