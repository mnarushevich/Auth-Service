<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('Reset password flow', function (): void {
    it('fails to send reset password email with invalid payload', function (): void {
        $this->postJson(
            getUrl(BaseWebTestCase::PASSWORD_SEND_RESET_LINK_ROUTE_NAME)
        )
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'The email field is required.',
                ]
            );
    });

    it('fails to reset password with invalid payload', function (array $payload, string $errorMessage): void {
        $this->postJson(
            getUrl(BaseWebTestCase::PASSWORD_RESET_ROUTE_NAME), $payload,
        )
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $errorMessage,
                ]
            );
    })->with(
        [
            [['email' => 'Test',  'token' => 'Test'], 'The email field must be a valid email address. (and 1 more error)'],
            [['email' => TEST_USER_EMAIL], 'The token field is required. (and 1 more error)'],
            [['email' => TEST_USER_EMAIL, 'token' => 'Test'], 'The password field is required.'],
            [['email' => TEST_USER_EMAIL, 'token' => 'Test', 'password' => 'Test'], 'The password field must be at least 8 characters. (and 1 more error)'],
            [['email' => TEST_USER_EMAIL, 'token' => 'Test', 'password' => 'Test1234', 'password_confirmation' => 'Test'], 'The password field confirmation does not match.'],
        ]);

    it('it sends email with password reset code', function (): void {
        Notification::fake();
        $email = fake()->email;
        $password = fake()->password();
        $user = createUser($email, $password);
        $resetToken = null;
        $this->postJson(
            getUrl(BaseWebTestCase::PASSWORD_SEND_RESET_LINK_ROUTE_NAME),
            ['email' => $user->email],
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'status' => Response::HTTP_OK,
                    'message' => 'Reset link sent successfully.',
                ]
            );

        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class,
            function ($notification, $channels) use (&$resetToken): bool {
                $resetToken = $notification->getToken();

                return in_array('mail', $channels);
            });

        expect($resetToken)->not->toBeNull();

        $newPassword = fake()->password();
        $this->postJson(
            getUrl(BaseWebTestCase::PASSWORD_RESET_ROUTE_NAME),
            [
                'email' => $user->email,
                'token' => $resetToken,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ],
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'status' => Response::HTTP_OK,
                    'message' => 'Password reset successful.',
                ]
            );
        $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => $user->email, 'password' => $newPassword]
        )->assertOk();

        // Attempt to login with old password
        $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => $user->email, 'password' => $password]
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
    });
});
