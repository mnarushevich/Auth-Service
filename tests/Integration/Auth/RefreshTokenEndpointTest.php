<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/refresh', function () {
    it('rejects refresh token for unauthenticated', function () {
        $this->postJson(getUrl(BaseWebTestCase::REFRESH_TOKEN_ROUTE_NAME))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('refreshes token for authenticated', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::REFRESH_TOKEN_ROUTE_NAME),
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(['access_token', 'token_type', 'expires_in']))
            ->assertJsonPath('token_type', 'bearer');
    })->group('with-auth');
})->group('auth');
