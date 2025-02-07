<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

final class LogoutController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout auth user",
     *     description="Logout auth user",
     *     operationId="authLogout",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function __invoke(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        Auth::logout();

        return response()->json(
            [
                'status' => Response::HTTP_OK,
                'message' => 'Successfully logged out.',
            ]
        );
    }
}
