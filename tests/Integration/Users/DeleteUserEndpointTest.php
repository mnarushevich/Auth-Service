<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use Database\Factories\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

use function PHPUnit\Framework\assertTrue;

describe('DELETE /users/{uuid}', function () {
    it('rejects for unauthorized', function () {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns not found for invalid UUID', function () {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(
                [
                    'status' => Response::HTTP_NOT_FOUND,
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
        )->assertStatus(Response::HTTP_NOT_FOUND);
    })->group('with-auth');

    it('denied to delete another user for non-admin', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $newUser = UserFactory::new()->create();

        assertTrue($this->user->role === RolesEnum::USER->value);
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => $newUser->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertStatus(Response::HTTP_FORBIDDEN);
    })->group('with-auth');
})->group('users');
