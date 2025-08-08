<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('GET /users/{uuid}', function (): void {
    it('rejects for unauthorized', function (): void {
        Cache::expects('remember')->never();
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

    it('returns not found for invalid UUID', function (): void {
        Cache::expects('remember')->once()->andThrows(new ModelNotFoundException);
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

    it('returns user data for valid UUID for USER role', function (): void {
        Cache::expects('remember')->once()->andReturn(new UserResource($this->user->load(['address', 'roles'])));
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::USER->value]);

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json->has('data.roles'))
            ->assertJsonMissingPath('data.permissions')
            ->assertJsonPath('data.uuid', $this->user->uuid)
            ->assertJsonPath('data.first_name', $this->user->first_name)
            ->assertJsonPath('data.last_name', $this->user->last_name)
            ->assertJsonPath('data.email', $this->user->email)
            ->assertJsonPath('data.roles', $this->user->getRoleNames()->toArray())
            ->assertJsonPath('data.address.country', $this->user->address->country);
    })->group('with-auth');

    it('returns user data for valid UUID for ADMIN role', function (): void {
        $this->user->removeRole(RolesEnum::USER);
        $this->user->assignRole(RolesEnum::ADMIN);
        Cache::expects('remember')->once()->andReturn(new UserResource($this->user->load(['address', 'roles', 'permissions'])));
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::ADMIN->value]);

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson => $json->hasAll(['data.roles', 'data.permissions']))
            ->assertJsonPath('data.uuid', $this->user->uuid)
            ->assertJsonPath('data.first_name', $this->user->first_name)
            ->assertJsonPath('data.last_name', $this->user->last_name)
            ->assertJsonPath('data.email', $this->user->email)
            ->assertJsonPath('data.roles', $this->user->getRoleNames()->toArray())
            ->assertJsonPath('data.permissions', $this->user->getAllPermissions()->pluck('name')->toArray())
            ->assertJsonPath('data.address.country', $this->user->address->country);
    })->group('with-auth');
})->group('users', 'with-roles-and-permissions');
