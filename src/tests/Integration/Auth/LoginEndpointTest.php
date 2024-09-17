<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use Illuminate\Testing\Fluent\AssertableJson;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/login', function () {
    it('rejects login for invalid credentials', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => 'email@test.com', 'password' => $this->mockPass]
        )
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status'  => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('rejects login for empty email', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => '', 'password' => $this->mockPass]
        )
            ->assertStatus(ResponseStatus::HTTP_BAD_REQUEST->value)
            ->assertJson(
                [
                    'status'  => ResponseStatus::HTTP_BAD_REQUEST->value,
                    'message' => 'The email field is required.'
                ]
            );
    });


    it('login with correct payload', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => $this->user->email, 'password' => $this->mockPass]
        )
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['access_token', 'token_type', 'expires_in']))
            ->assertJsonPath('token_type', 'bearer')
            ->assertJsonPath('expires_in', 3600);
    });
})->group('auth', 'login');

