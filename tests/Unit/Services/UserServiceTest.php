<?php

declare(strict_types=1);

use App\Services\UserService;
use Database\Factories\UserFactory;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->refreshApplication();
});
describe('UserService::publishUserCreatedEvent method', function (): void {
    it('publishes correct message to Kafka', function (): void {
        Kafka::fake();

        $now = now();
        $uuid = Str::uuid()->toString();
        $user = UserFactory::new()
            ->withUserRole()
            ->make([
                'uuid' => $uuid,
                'email' => 'test@test.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'created_at' => $now,
            ]);

        (new UserService)->publishUserCreatedEvent($user);

        $expectedMessage = new Message(
            headers: ['event-type' => 'user.created'],
            body: json_encode([
                'uuid' => $uuid,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'roles' => $user->getRoleNames()->toArray(),
                'created_at' => $now->toIso8601String(),
            ]),
            key: $user->getKey(),
        );

        Kafka::assertPublished($expectedMessage);
    });
});
