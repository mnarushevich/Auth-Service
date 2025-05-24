<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\GuardsEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /roles', function () {
    it('rejects for unauthorized', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_ROLE_ROUTE_NAME),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('fails to create role with invalid payload', function (array $payload, string $errorMessage) {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_ROLE_ROUTE_NAME),
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
            [['name' => RolesEnum::USER->value], 'The guard name field is required.'],
            [
                ['name' => RolesEnum::USER->value, 'guard_name' => 1234],
                'The selected guard name is invalid.',
            ],
        ])->group('with-auth');

    it('returns bad request response for existing role name', function () {
        $role = Role::create([
            'name' => RolesEnum::USER->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_ROLE_ROUTE_NAME),
            data: [
                'name' => $role->name,
                'guard_name' => GuardsEnum::API->value,
            ],
            headers: getAuthorizationHeader($this->token),
        )->assertBadRequest()
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => "Role with name `$role->name` already exist",
                ]
            );
    })->group('with-auth');

    it('crates role with valid name and guard name', function () {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_ROLE_ROUTE_NAME),
            data: [
                'name' => RolesEnum::USER->value,
                'guard_name' => GuardsEnum::API->value,
            ],
            headers: getAuthorizationHeader($this->token),
        )
            ->assertCreated()
            ->assertJsonFragment([
                'data' => [
                    'name' => RolesEnum::USER->value,
                    'guard_name' => GuardsEnum::API->value,
                ],
            ]);

        expect(Role::query()
            ->where('name', RolesEnum::USER->value)
            ->where('guard_name', GuardsEnum::API->value)
            ->exists())
            ->toBeTrue();
    })->group('with-auth');
})->group('roles');
