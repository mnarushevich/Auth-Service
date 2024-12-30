<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/verify', function () {
    it('rejects auth token verify for unauthenticated', function () {
        $this->postJson(getUrl(BaseWebTestCase::VERIFY_TOKEN_ROUTE_NAME))
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('verifies token for authenticated', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::VERIFY_TOKEN_ROUTE_NAME),
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertExactJson(['status' => true]);
    })->group('with-auth');
})->group('auth');
