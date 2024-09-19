<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use tests\Integration\BaseWebTestCase;

describe('DELETE /users/{uuid}', function () {
    it('rejects for unauthorized', function () {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status'  => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns not found for invalid UUID', function () {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertStatus(ResponseStatus::NOT_FOUND->value)
            ->assertJson(
                [
                    'status'  => ResponseStatus::NOT_FOUND->value,
                    'message' => 'Not found.',
                ]
            );
    })->group('with-auth');

    it('deletes user with valid UUID', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertStatus(ResponseStatus::NOT_FOUND->value);
    })->group('with-auth');
})->group('users');
