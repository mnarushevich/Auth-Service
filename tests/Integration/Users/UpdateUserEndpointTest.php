<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\ResponseStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use tests\Integration\BaseWebTestCase;

describe('PATCH /users/{uuid}', function () {
    it('rejects for unauthorized', function () {
        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(ResponseStatus::UNAUTHORIZED->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::UNAUTHORIZED->value,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('rejects with invalid payload data', function (array $payload, string $errorMessage) {
        $alreadyUsedEmail = 'test-used@test.com';
        User::factory()->create(
            [
                'email' => $alreadyUsedEmail,
                'password' => Hash::make($this->mockPass),
            ]
        );

        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            $payload,
        )
            ->assertStatus(ResponseStatus::HTTP_BAD_REQUEST->value)
            ->assertJson(
                [
                    'status' => ResponseStatus::HTTP_BAD_REQUEST->value,
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

    it('updates user with valid payload', function () {
        $mockEmail = fake()->email();
        $mockFirstName = fake()->firstName();
        $mockLastName = fake()->lastName();
        $mockPhoneNumber = fake()->phoneNumber();
        $this->patchJson(
            getUrl(BaseWebTestCase::UPDATE_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            [
                'email' => $mockEmail,
                'first_name' => $mockFirstName,
                'last_name' => $mockLastName,
                'phone' => $mockPhoneNumber,
                'password' => fake()->password(),
                'type' => UserType::USER->value,
            ],
        )
            ->assertOk()
            ->assertJsonPath('data.first_name', $mockFirstName)
            ->assertJsonPath('data.last_name', $mockLastName)
            ->assertJsonPath('data.type', UserType::USER->value)
            ->assertJsonPath('data.phone', $mockPhoneNumber)
            ->assertJsonPath('data.email', $mockEmail);
    })->group('with-auth');
})->group('users');
