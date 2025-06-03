<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Post(
 *     path="/auth/reset-password",
 *     summary="Reset password",
 *     description="Reset user's password using the token received via email",
 *     tags={"Auth"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *
 *         @OA\JsonContent(
 *             required={"token", "email", "password", "password_confirmation"},
 *
 *             @OA\Property(property="token", type="string", example="1234-5678-abcd-efgh"),
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="newPassword123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="newPassword123")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Password reset successful.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Invalid token or email",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(property="message", type="string", example="Invalid token or email.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(property="message", type="string", example="The password confirmation does not match."),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
 */
final class ResetPasswordController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $validated,
            function ($user, $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['status' => Response::HTTP_OK, 'message' => 'Password reset successful.']);
        }

        return response()->json(['message' => 'Invalid token or email.'], Response::HTTP_BAD_REQUEST);
    }
}
