<?php

declare(strict_types=1);

use App\Enums\RolesEnum;
use App\Models\User;
use App\Services\UserService;
use Database\Factories\UserFactory;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    if (! defined('RD_KAFKA_PARTITION_UA')) {
        define('RD_KAFKA_PARTITION_UA', 1);
    }

    $this->refreshApplication();
});
describe('UserService::publishUserCreatedEvent method', function (): void {
    it('publishes correct message to Kafka', function (): void {
        Kafka::fake();

        $now = now();
        $uuid = Str::uuid()->toString();
        $user = UserFactory::new()->make([
            'uuid' => $uuid,
            'email' => 'test@test.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => $now,
        ]);

        /** @var User|Mockery $user */
        $user = Mockery::mock($user);
        $user->shouldReceive('getRoleNames')
            ->once()
            ->andReturn(collect([RolesEnum::USER]));

        (new UserService)->publishUserCreatedEvent($user);

        $expectedMessage = new Message(
            headers: ['event-type' => 'user-created'],
            body: [
                'id' => $uuid,
                'email' => 'test@test.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'roles' => [RolesEnum::USER],
                'created_at' => $now->toIso8601String(),
            ]
        );

        Kafka::assertPublished($expectedMessage);
    });
});
