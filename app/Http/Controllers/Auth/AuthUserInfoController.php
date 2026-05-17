<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use LogicException;
use Tymon\JWTAuth\JWTGuard;

final class AuthUserInfoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/me",
     *     summary="Get auth user data",
     *     description="Get authenticated user data",
     *     operationId="authMe",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function __invoke(): JsonResponse
    {
        $guard = Auth::guard('api');

        if (! $guard instanceof JWTGuard) {
            throw new LogicException('The api guard must be a JWT guard.');
        }

        return response()->json([
            'user' => new UserResource(
                resource: Auth::user()->load('roles', 'permissions'),
                isAuthUser: true
            ),
            'payload' => $guard->payload(),
        ]);
    }
}
