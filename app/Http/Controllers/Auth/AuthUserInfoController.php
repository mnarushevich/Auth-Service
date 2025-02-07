<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
        return response()->json([
            'user' => Auth::user(),
            'payload' => Auth::payload(),
        ]);
    }
}
