<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/verify', function () {
    it('rejects auth token verify for unauthenticated', function () {
        $this->postJson(getUrl(BaseWebTestCase::VERIFY_TOKEN_ROUTE_NAME))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
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
