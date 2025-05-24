<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\GuardsEnum;
use App\Enums\PermissionsEnum;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /permissions', function () {
    it('rejects for unauthorized', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_PERMISSION_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('fails to create permission with invalid payload', function (array $payload, string $errorMessage) {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_PERMISSION_ROUTE_NAME),
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
            [
                ['name' => PermissionsEnum::USERS_VIEW->value, 'guard_name' => 1234],
                'The selected guard name is invalid.',
            ],
        ])->group('with-auth');

    it('returns bad request response for existing permission name', function () {
        $permission = Permission::create([
            'name' => PermissionsEnum::USERS_VIEW->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_PERMISSION_ROUTE_NAME),
            data: [
                'name' => $permission->name,
                'guard_name' => GuardsEnum::API->value,
            ],
            headers: getAuthorizationHeader($this->token),
        )->assertBadRequest()
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => "Permission with name `$permission->name` already exist",
                ]
            );
    })->group('with-auth');

    it('crates permission with valid name and guard name', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_PERMISSION_ROUTE_NAME),
            data: [
                'name' => PermissionsEnum::USERS_VIEW->value,
                'guard_name' => GuardsEnum::API->value,
            ],
            headers: getAuthorizationHeader($this->token),
        )
            ->assertCreated()
            ->assertJsonFragment([
                'data' => [
                    'name' => PermissionsEnum::USERS_VIEW->value,
                    'guard_name' => GuardsEnum::API->value,
                ],
            ]);

        expect(Permission::query()
            ->where('name', PermissionsEnum::USERS_VIEW->value)
            ->where('guard_name', GuardsEnum::API->value)
            ->exists())
            ->toBeTrue();
    })->group('with-auth');
})->group('permissions');
