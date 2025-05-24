<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

final class StoreUserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

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
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user->assignRole(RolesEnum::USER);
        $user->address()->create([
            'country' => $request->input('address.country'),
        ]);

        $this->userService->publishUserCreatedEvent($user);

        $user->refresh()->load('address');

        return new UserResource($user);
    }
}
