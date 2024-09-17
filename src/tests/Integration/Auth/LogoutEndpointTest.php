<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/me', function () {
    it('rejects logout user for unauthenticated', function () {
        $this->postJson(getUrl(BaseWebTestCase::LOGOUT_ROUTE_NAME))
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('logout user for authenticated', function () {
        $response = $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => $this->user->email, 'password' => $this->mockPass]
        )->decodeResponseJson();

        $this->postJson(
            getUrl(BaseWebTestCase::LOGOUT_ROUTE_NAME),
            headers: ['Authorization' => sprintf('Bearer %s', $response['access_token'])]
        )
            ->assertOk()
            ->assertJson(
                [
                    'status' => ResponseStatus::HTTP_OK->value,
                    'message' => 'Successfully logged out.',
                ]
            );
    });
})->group('auth', 'logout');
