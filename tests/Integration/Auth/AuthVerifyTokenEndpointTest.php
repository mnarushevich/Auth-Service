<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\AppRouteNamesEnum;
use Symfony\Component\HttpFoundation\Response;

describe('POST /auth/verify', function (): void {
    it('rejects auth token verify for unauthenticated', function (): void {
        $this->postJson(getUrl(AppRouteNamesEnum::VERIFY_TOKEN_ROUTE_NAME->value))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('verifies token for authenticated', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::VERIFY_TOKEN_ROUTE_NAME->value),
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertExactJson(['status' => true]);
    })->group('with-auth');
})->group('auth');
