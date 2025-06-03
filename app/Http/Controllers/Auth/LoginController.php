<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login user",
     *     description="Login user via email and password",
     *     operationId="authLogin",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@test.com", "password": "password"}
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     *
     * @throws AuthenticationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->first();

        if ($user === null || ! Hash::check($validated['password'], $user->password)) {
            throw new AuthenticationException;
        }

        if (! $token = Auth::claims(['internal_user_id' => $user->uuid])->attempt($validated)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    private function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }
}
