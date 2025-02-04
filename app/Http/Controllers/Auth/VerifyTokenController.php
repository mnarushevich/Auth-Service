<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class VerifyTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/verify",
     *     summary="Verify token of authenticated user",
     *     description="Verify token of authenticated user",
     *     operationId="verify",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(['status' => Auth::check()]);
    }
}
