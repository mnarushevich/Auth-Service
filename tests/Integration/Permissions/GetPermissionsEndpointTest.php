<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\GuardsEnum;
use App\Enums\PermissionsEnum;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('GET /permissions', function () {
    it('rejects for unauthorized', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_PERMISSIONS_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns permissions list', function () {
        $permission = Permission::create([
            'name' => PermissionsEnum::USERS_VIEW->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->getJson(
            getUrl(BaseWebTestCase::GET_PERMISSIONS_ROUTE_NAME),
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
                            'name',
                            'guard_name',
                        ],
                    ],
                ]
            )
            ->assertJsonFragment(['name' => $permission->name, 'guard_name' => $permission->guard_name])
            ->assertJsonPath('meta.count', 1);
    })->group('with-auth');
})->group('permissions');
