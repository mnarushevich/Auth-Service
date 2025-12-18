<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\AppRouteNamesEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;

describe('POST /auth/refresh', function (): void {
    it('rejects refresh token for unauthenticated', function (): void {
        $this->postJson(getUrl(AppRouteNamesEnum::REFRESH_TOKEN_ROUTE_NAME->value))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('refreshes token for authenticated', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::REFRESH_TOKEN_ROUTE_NAME->value),
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson => $json->hasAll(['access_token', 'token_type', 'expires_in']))
            ->assertJsonPath('token_type', 'bearer');
    })->group('with-auth');
})->group('auth');
