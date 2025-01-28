<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use Database\Factories\UserFactory;
use tests\Integration\BaseWebTestCase;

describe('GET /users', function () {
    it('rejects for unauthorized', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USERS_ROUTE_NAME),
        )
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns users for authenticated', function () {
        UserFactory::new()->count(10)->create();
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USERS_ROUTE_NAME),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertOk()
            ->assertJsonStructure(
                [
                    'meta' => [
                        'count',
                    ],
                    'data' => [
                        '*' => [
                            'uuid',
                            'first_name',
                            'last_name',
                            'role',
                            'phone',
                            'email',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ]
            )
            ->assertJsonFragment(['uuid' => $this->user->uuid])
            ->assertJsonPath('meta.count', 11);
    })->group('with-auth');
})->group('users');
