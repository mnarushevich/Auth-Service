<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use LogicException;
use Tymon\JWTAuth\JWTGuard;

final class RefreshTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Refresh auth user JWT token",
     *     description="Refresh auth user JWT token",
     *     operationId="authRefresh",
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

        return $this->respondWithToken($guard, $guard->refresh());
    }

    private function respondWithToken(JWTGuard $guard, string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
        ]);
    }
}
