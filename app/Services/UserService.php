<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Junges\Kafka\Exceptions\MessageIdNotSet;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

final class UserService
{
    /**
     * @throws MessageIdNotSet
     */
    public function publishUserCreatedEvent(User $user): void
    {
        try {
            $message = new Message(
                headers: ['event-type' => 'user-created'],
                body: json_encode([
                    'id' => $user->uuid,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'roles' => $user->getRoleNames()->toArray(),
                    'created_at' => $user->created_at->toIso8601String(),
                ]),
                key: $user->getKey(),
            );

            Kafka::publish(config('kafka.brokers'))
                ->onTopic('default')
                ->withMessage($message)
                ->send();
        } catch (\Exception $exception) {
            throw new MessageIdNotSet($exception->getMessage());
        }
    }
}
