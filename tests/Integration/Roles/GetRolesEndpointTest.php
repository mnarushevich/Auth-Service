<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\GuardsEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('GET /roles', function () {
    it('rejects for unauthorized', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_ROLES_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns roles list', function () {
        $role = Role::create([
            'name' => RolesEnum::USER->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->getJson(
            getUrl(BaseWebTestCase::GET_ROLES_ROUTE_NAME),
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
            ->assertJsonFragment(['name' => $role->name, 'guard_name' => $role->guard_name])
            ->assertJsonPath('meta.count', 1);
    })->group('with-auth');
})->group('roles');
