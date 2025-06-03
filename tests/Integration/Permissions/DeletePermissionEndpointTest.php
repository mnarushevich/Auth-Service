<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\GuardsEnum;
use App\Enums\PermissionsEnum;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('DELETE /permissions', function (): void {
    it('rejects for unauthorized', function (): void {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_PERMISSIONS_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('fails to delete permission with invalid payload', function (array $payload, string $errorMessage): void {
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_PERMISSIONS_ROUTE_NAME),
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

    it('returns not found for invalid permission name', function (): void {
        $invalidName = 'invalid-name';
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_PERMISSIONS_ROUTE_NAME),
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

    it('deletes permission by name and guard name', function (): void {
        $permission = Permission::create([
            'name' => PermissionsEnum::USERS_VIEW->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_PERMISSIONS_ROUTE_NAME),
            data: [
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
            ],
            headers: getAuthorizationHeader($this->token),
        )->assertOk();
        expect(Permission::query()
            ->where('name', $permission->name)
            ->where('guard_name', $permission->guard_name)
            ->exists())
            ->toBeFalse();
    })->group('with-auth');
})->group('permissions');
