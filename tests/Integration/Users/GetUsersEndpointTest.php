<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use Database\Factories\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('GET /users', function () {
    it('rejects for unauthorized', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USERS_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns users for authenticated', function () {
        UserFactory::new()->count(10)->withUserRole()->create();
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
                            'phone',
                            'email',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ]
            )
            ->assertJsonMissingPath('data.roles')
            ->assertJsonMissingPath('data.permissions')
            ->assertJsonFragment(['uuid' => $this->user->uuid])
            ->assertJsonPath('meta.count', 11);
    })->group('with-auth');
})->group('users', 'with-roles-and-permissions');
