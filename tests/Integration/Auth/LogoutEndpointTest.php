<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/me', function () {
    it('rejects logout user for unauthenticated', function () {
        $this->postJson(getUrl(BaseWebTestCase::LOGOUT_ROUTE_NAME))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('logout user for authenticated', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::LOGOUT_ROUTE_NAME),
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
