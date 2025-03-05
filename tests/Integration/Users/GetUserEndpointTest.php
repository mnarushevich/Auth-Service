<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('GET /users/{uuid}', function () {
    it('rejects for unauthorized', function () {
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
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
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
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

    it('returns user data for valid UUID for USER role', function () {
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::USER->value]);

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('data.roles'))
            ->assertJsonMissingPath('data.permissions')
            ->assertJsonPath('data.uuid', $this->user->uuid)
            ->assertJsonPath('data.first_name', $this->user->first_name)
            ->assertJsonPath('data.last_name', $this->user->last_name)
            ->assertJsonPath('data.email', $this->user->email)
            ->assertJsonPath('data.roles', $this->user->getRoleNames()->toArray())
            ->assertJsonPath('data.address.country', $this->user->address->country);
    })->group('with-auth');

    it('returns user data for valid UUID for ADMIN role', function () {
        $this->user->removeRole(RolesEnum::USER);
        $this->user->assignRole(RolesEnum::ADMIN);
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::ADMIN->value]);

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(['data.roles', 'data.permissions']))
            ->assertJsonPath('data.uuid', $this->user->uuid)
            ->assertJsonPath('data.first_name', $this->user->first_name)
            ->assertJsonPath('data.last_name', $this->user->last_name)
            ->assertJsonPath('data.email', $this->user->email)
            ->assertJsonPath('data.roles', $this->user->getRoleNames()->toArray())
            ->assertJsonPath('data.permissions', $this->user->getAllPermissions()->pluck('name')->toArray())
            ->assertJsonPath('data.address.country', $this->user->address->country);
    })->group('with-auth');
})->group('users', 'with-roles-and-permissions');
