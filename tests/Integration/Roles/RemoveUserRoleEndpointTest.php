<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /users/{uuid}/remove-role', function (): void {
    it('rejects for unauthorized', function (): void {
        $this->postJson(
            getUrl(BaseWebTestCase::REMOVE_USER_ROLE_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('fails to remove a role with invalid payload', function (array $payload, string $errorMessage): void {
        $this->postJson(
            getUrl(BaseWebTestCase::REMOVE_USER_ROLE_ROUTE_NAME, ['user' => $this->user]),
            data: $payload,
            headers: getAuthorizationHeader($this->token),
        )
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $errorMessage,
                ]
            );
    })->with(
        [
            [[], 'The role name field is required.'],
            [['role_name' => 123], 'The role name field must be a string.'],
        ])->group('with-auth');

    it('returns not found response for non-existing user', function (): void {
        $invalidUuid = 'invalid-uuid';
        $this->postJson(
            getUrl(BaseWebTestCase::REMOVE_USER_ROLE_ROUTE_NAME, ['user' => $invalidUuid]),
            data: ['role_name' => RolesEnum::USER->value],
            headers: getAuthorizationHeader($this->token),
        )->assertNotFound()
            ->assertJson(
                [
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Not found.',
                ]
            );
    })->group('with-auth');

    it('returns bad request response for non-existing role name', function (): void {
        $invalidRoleName = 'invalid-role-name';
        $this->postJson(
            getUrl(BaseWebTestCase::REMOVE_USER_ROLE_ROUTE_NAME, ['user' => $this->user]),
            data: ['role_name' => $invalidRoleName],
            headers: getAuthorizationHeader($this->token),
        )->assertBadRequest()
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => sprintf('Role with name `%s` does not exist', $invalidRoleName),
                ]
            );
    })->group('with-auth');

    it('fails to remove the same role if not assigned to user', function (): void {
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::USER->value]);

        $this->postJson(
            getUrl(BaseWebTestCase::REMOVE_USER_ROLE_ROUTE_NAME, ['user' => $this->user]),
            data: ['role_name' => RolesEnum::ADMIN->value],
            headers: getAuthorizationHeader($this->token),
        )
            ->assertBadRequest()
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => sprintf('User does not have role `%s`', RolesEnum::ADMIN->value),
                ]
            );
    })->group('with-auth');

    it('removes a role from user', function (): void {
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::USER->value]);

        $this->postJson(
            getUrl(BaseWebTestCase::REMOVE_USER_ROLE_ROUTE_NAME, ['user' => $this->user]),
            data: ['role_name' => RolesEnum::USER->value],
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertJsonFragment([
                'status' => Response::HTTP_OK,
                'message' => 'Role removed successfully',
            ]);

        $this->user->refresh();
        expect($this->user->hasRole(RolesEnum::USER))->toBeFalse();
    })->group('with-auth');
})->group('roles', 'with-roles-and-permissions');
