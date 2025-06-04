<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /auth/me', function (): void {
    it('rejects auth user data for unauthenticated', function (): void {
        $this->postJson(getUrl(BaseWebTestCase::USER_INFO_ROUTE_NAME))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('gets auth user data for authenticated', function (): void {
        expect($this->user->getRoleNames()->toArray())
            ->toBeArray()
            ->toHaveCount(1)
            ->toEqual([RolesEnum::USER->value]);

        $this->postJson(
            getUrl(BaseWebTestCase::USER_INFO_ROUTE_NAME),
            headers: getAuthorizationHeader($this->token),
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson => $json->hasAll(['user', 'payload']))
            ->assertJsonPath('user.uuid', $this->user->uuid)
            ->assertJsonPath('user.email', $this->user->email)
            ->assertJsonPath('user.roles', $this->user->getRoleNames()->toArray())
            ->assertJsonPath('user.permissions', $this->user->getAllPermissions()->pluck('name')->toArray())
            ->assertJsonPath('payload.internal_user_id', $this->user->uuid);
    })->group('with-auth');
})->group('auth', 'with-roles-and-permissions');
