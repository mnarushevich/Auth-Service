<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Post(
 *     path="/auth/forgot-password",
 *     summary="Send password reset link",
 *     description="Sends a password reset link to the specified email address",
 *     tags={"Auth"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *
 *         @OA\JsonContent(
 *             required={"email"},
 *
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Reset link sent successfully",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Reset link sent successfully.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(property="message", type="string", example="The selected email is invalid"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
 */
final class SendPasswordResetLinkController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['status' => Response::HTTP_OK, 'message' => 'Reset link sent successfully.']);
            }

            return response()->json(['message' => 'Failed to send reset link.'], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
