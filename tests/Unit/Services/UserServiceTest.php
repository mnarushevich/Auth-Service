<?php

declare(strict_types=1);

use App\Enums\RolesEnum;
use App\Models\User;
use App\Services\UserService;
use Junges\Kafka\Facades\Kafka;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    if (! defined('RD_KAFKA_PARTITION_UA')) {
        define('RD_KAFKA_PARTITION_UA', 1);
    }
    $this->refreshApplication();
});
describe('UserService::publishUserCreatedEvent method', function () {
    it('publishes correct message to Kafka', function () {
        Kafka::fake();

        $now = now();
        $user = new User([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
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

        Kafka::assertPublished();
    });
});
