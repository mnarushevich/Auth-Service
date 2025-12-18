<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\AppRouteNamesEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;

describe('POST /auth/login', function (): void {
    it('rejects login for invalid credentials', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::LOGIN_ROUTE_NAME->value),
            ['email' => 'email@test.com', 'password' => $this->mockPass]
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('rejects login for empty email', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::LOGIN_ROUTE_NAME->value),
            ['email' => '', 'password' => $this->mockPass]
        )
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'The email field is required.',
                ]
            );
    });

    it('login with correct payload', function (): void {
        $this->postJson(
            getUrl(AppRouteNamesEnum::LOGIN_ROUTE_NAME->value),
            ['email' => $this->user->email, 'password' => $this->mockPass]
        )
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson => $json->hasAll(['access_token', 'token_type', 'expires_in']))
            ->assertJsonPath('token_type', 'bearer')
            ->assertJsonPath('expires_in', 3600);
    });
})->group('auth', 'login');
