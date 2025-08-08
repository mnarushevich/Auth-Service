<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use App\Http\Resources\UserResource;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('PATCH /users/{uuid}', function (): void {
    it('rejects for unauthorized', function (): void {
        Cache::expects('forget')->never();
        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('rejects with invalid payload data', function (array $payload, string $errorMessage): void {
        $alreadyUsedEmail = 'test-used@test.com';
        UserFactory::new()->create(
            [
                'email' => $alreadyUsedEmail,
                'password' => Hash::make($this->mockPass),
            ]
        );
        Cache::expects('forget')->never();

        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            $payload,
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
            [
                [
                    'email' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'country' => '',
                ],
                'The email field is required. (and 3 more errors)',
            ],
            [
                [
                    'email' => 'test-used@test.com',
                ],
                'The email has already been taken.',
            ],
        ])->group('with-auth');

    it('updates user with valid payload', function (): void {
        $mockEmail = fake()->email();
        $mockFirstName = fake()->firstName();
        $mockLastName = fake()->lastName();
        $mockPhoneNumber = fake()->phoneNumber();
        Cache::expects('forget')->once()->with(sprintf('user-%s', $this->user->uuid));
        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            [
                'email' => $mockEmail,
                'first_name' => $mockFirstName,
                'last_name' => $mockLastName,
                'phone' => $mockPhoneNumber,
                'password' => fake()->password(),
            ],
        )
            ->assertOk()
            ->assertJsonPath('data.first_name', $mockFirstName)
            ->assertJsonPath('data.last_name', $mockLastName)
            ->assertJsonPath('data.phone', $mockPhoneNumber)
            ->assertJsonPath('data.email', $mockEmail);
    })->group('with-auth');

    it('denied to update another user for non-admin', function (): void {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(new UserResource($this->user));
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $newUser = UserFactory::new()->withUserRole()->create();
        Cache::expects('forget')->never()->with(sprintf('user-%s', $newUser->uuid));
        expect($newUser->getRoleNames())->toContain(RolesEnum::USER->value);
        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => $newUser->uuid]),
            [
                'email' => fake()->email,
                'first_name' => fake()->firstName,
                'last_name' => fake()->lastName,
            ],
        )->assertStatus(Response::HTTP_FORBIDDEN);
    })->group('with-auth');
})->group('users', 'with-roles-and-permissions');
