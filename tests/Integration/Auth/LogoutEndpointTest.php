<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\AppRouteNamesEnum;
use Symfony\Component\HttpFoundation\Response;

describe('POST /auth/me', function (): void {
    it('rejects logout user for unauthenticated', function (): void {
        $this->postJson(getUrl(AppRouteNamesEnum::LOGOUT_ROUTE_NAME->value))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('logout user for authenticated', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::LOGOUT_ROUTE_NAME->value),
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertJson(
                [
                    'status' => Response::HTTP_OK,
                    'message' => 'Successfully logged out.',
                ]
            );
    })->group('with-auth');
})->group('auth', 'logout');
