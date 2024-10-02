<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use tests\Integration\BaseWebTestCase;

describe('GET /users/{uuid}', function () {
    it('rejects for unauthorized', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns not found for invalid UUID', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertStatus(ResponseStatus::NOT_FOUND->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::NOT_FOUND->value,
                    'message' => 'Not found.',
                ]
            );
    })->group('with-auth');

    it('returns user data for valid UUID', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertOk()
            ->assertJsonPath('data.uuid', $this->user->uuid)
            ->assertJsonPath('data.first_name', $this->user->first_name)
            ->assertJsonPath('data.last_name', $this->user->last_name)
            ->assertJsonPath('data.type', $this->user->type)
            ->assertJsonPath('data.email', $this->user->email);
    })->group('with-auth');
})->group('users');
