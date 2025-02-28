<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\UserRole;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /users', function () {
    it('rejects with invalid payload data', function (array $payload, string $errorMessage) {
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_USER_ROUTE_NAME), $payload,
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
                    'email' => 'Test',
                    'first_name' => fake()->firstName(),
                    'password' => fake()->password(),
                    'address' => [
                        'country' => fake()->country(),
                    ],
                ],
                'The email field must be a valid email address.',
            ],
            [
                [
                    'email' => fake()->email(),
                    'password' => fake()->password(),
                    'address' => [
                        'country' => fake()->country(),
                    ],
                ],
                'The first name field is required.',
            ],
            [
                [
                    'email' => fake()->email(),
                    'first_name' => fake()->firstName(),
                    'address' => [
                        'country' => fake()->country(),
                    ],
                ],
                'The password field is required.',
            ],
            [
                [],
                'The email field is required. (and 3 more errors)',
            ],
            [
                [
                    'email' => 'test@test.com',
                    'first_name' => fake()->firstName(),
                    'password' => fake()->password(),
                    'address' => [
                        'country' => fake()->country(),
                    ],
                ],
                'The email has already been taken.',
            ],
            [
                [
                    'email' => fake()->email(),
                    'first_name' => fake()->firstName(),
                    'password' => fake()->password(),
                ],
                'The address.country field is required.',
            ],
        ]);

    it('creates new user with valid payload', function () {
        $mockEmail = fake()->email();
        $mockFirstName = fake()->firstName();
        $mockLastName = fake()->lastName();
        $mockCountry = fake()->country();
        $this->postJson(
            getUrl(BaseWebTestCase::CREATE_USER_ROUTE_NAME),
            [
                'email' => $mockEmail,
                'first_name' => $mockFirstName,
                'last_name' => $mockLastName,
                'password' => fake()->password(),
                'type' => UserRole::USER->value,
                'address' => [
                    'country' => $mockCountry,
                ],
            ],
        )
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.first_name', $mockFirstName)
            ->assertJsonPath('data.last_name', $mockLastName)
            ->assertJsonPath('data.role', UserRole::USER->value)
            ->assertJsonPath('data.address.country', $mockCountry)
            ->assertJsonPath('data.email', $mockEmail);
    });
})->group('users');
