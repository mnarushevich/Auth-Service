<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['store']),
        ];
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get a list of users",
     *     operationId="usersList",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index()
    {
        return new UserCollection(User::where('type', UserType::USER->value)->get());
    }

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
    public function store(StoreUserRequest $request)
    {
        $user = User::create(
            array_merge(
                $request->only('first_name', 'last_name', 'email', 'country', 'phone', 'type'),
                [
                    'password' => Hash::make($request->password),
                    'type' => $request->type ?? UserType::USER->value,
                ]
            )
        );

        return new UserResource($user);
    }

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
    public function show(string $uuid)
    {
        return new UserResource(User::where('uuid', $uuid)->where('type', UserType::USER->value)->firstOrFail());
    }

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
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update(
            $request->only('first_name', 'last_name', 'email', 'country', 'phone', 'type'),
        );

        return new UserResource($user);
    }

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
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'ok',
            'message' => sprintf('User with uuid %s was deleted.', $user->uuid),
        ]);
    }
}
