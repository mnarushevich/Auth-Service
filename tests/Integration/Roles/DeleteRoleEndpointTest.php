<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\GuardsEnum;
use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('DELETE /roles', function () {
    it('rejects for unauthorized', function () {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_ROLES_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('fails to delete role with invalid payload', function (array $payload, string $errorMessage) {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_ROLES_ROUTE_NAME),
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
            [[], 'The name field is required. (and 1 more error)'],
            [['name' => PermissionsEnum::USERS_VIEW->value], 'The guard name field is required.'],
            [['name' => PermissionsEnum::USERS_VIEW->value, 'guard_name' => 1234], 'The selected guard name is invalid.'],
        ])->group('with-auth');

    it('returns not found for invalid role name', function () {
        $invalidName = 'invalid-name';
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_ROLES_ROUTE_NAME),
            data: [
                'name' => $invalidName,
                'guard_name' => GuardsEnum::API->value,
            ],
            headers: getAuthorizationHeader($this->token),
        )->assertNotFound()
            ->assertJson(
                [
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Not found.',
                ]
            );
    })->group('with-auth');

    it('deletes role by name and guard name', function () {
        $role = Role::create([
            'name' => RolesEnum::USER->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_ROLES_ROUTE_NAME),
            data: [
                'name' => $role->name,
                'guard_name' => $role->guard_name,
            ],
            headers: getAuthorizationHeader($this->token),
        )->assertOk()
            ->assertJson(
                [
                    'status' => Response::HTTP_OK,
                    'message' => sprintf('Role with name `%s` and guard name `%s` was deleted.', $role->name, $role->guard_name),
                ]
            );
        expect(Role::query()
            ->where('name', $role->name)
            ->where('guard_name', $role->guard_name)
            ->exists())
            ->toBeFalse();
    })->group('with-auth');
})->group('roles');
