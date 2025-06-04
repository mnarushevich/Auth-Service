<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use Junges\Kafka\Facades\Kafka;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('POST /users', function (): void {
    beforeEach(function (): void {
        $this->mockPass = fake()->password();
    });

    it('rejects with invalid payload data', function (): void {
        $dataset = [
            [
                [
                    'email' => 'Test',
                    'first_name' => fake()->firstName(),
                    'password' => $this->mockPass,
                    'password_confirmation' => $this->mockPass,
                    'address' => [
                        'country' => fake()->country(),
                    ],
                ],
                'The email field must be a valid email address.',
            ],
            [
                [
                    'email' => fake()->email(),
                    'password' => $this->mockPass,
                    'password_confirmation' => $this->mockPass,
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
                    'password' => $this->mockPass,
                    'password_confirmation' => $this->mockPass,
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
                    'password' => $this->mockPass,
                    'password_confirmation' => $this->mockPass,
                ],
                'The address.country field is required.',
            ],
        ];

        foreach ($dataset as [$payload, $errorMessage]) {
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
        }
    });

    it('creates new user with valid payload', function (): void {
        Kafka::fake();

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
                'password' => $this->mockPass,
                'password_confirmation' => $this->mockPass,
                'type' => RolesEnum::USER->value,
                'address' => [
                    'country' => $mockCountry,
                ],
            ],
        )
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.first_name', $mockFirstName)
            ->assertJsonPath('data.last_name', $mockLastName)
            ->assertJsonPath('data.address.country', $mockCountry)
            ->assertJsonPath('data.email', $mockEmail);

        Kafka::assertPublishedOn('default');
    });
})->group('users', 'with-roles-and-permissions');
