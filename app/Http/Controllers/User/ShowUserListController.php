<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;

final class ShowUserListController extends Controller
{
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
    public function __invoke(): UserCollection
    {
        return new UserCollection(
            User::query()
                ->where('role', RolesEnum::USER->value)
                ->paginate(15),
        );
    }
}
