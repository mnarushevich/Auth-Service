<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\AppRouteNamesEnum;
use App\Enums\GuardsEnum;
use App\Enums\RolesEnum;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;

describe('POST /roles', function (): void {
    it('rejects for unauthorized', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::CREATE_ROLE_ROUTE_NAME->value),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('fails to create role with invalid payload', function (array $payload, string $errorMessage): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::CREATE_ROLE_ROUTE_NAME->value),
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
        ]
    )->group('with-auth');

    it('returns bad request response for existing role name', function (): void {
        $role = Role::create([
            'name' => RolesEnum::USER->value,
            'guard_name' => GuardsEnum::API->value,
        ]);

        $this->postJson(
            getUrl(AppRouteNamesEnum::CREATE_ROLE_ROUTE_NAME->value),
            data: [
                'name' => $role->name,
                'guard_name' => GuardsEnum::API->value,
            ],
            headers: getAuthorizationHeader($this->token),
        )->assertBadRequest()
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => sprintf('Role with name `%s` already exist', $role->name),
                ]
            );
    })->group('with-auth');

    it('crates role with valid name and guard name', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::CREATE_ROLE_ROUTE_NAME->value),
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
