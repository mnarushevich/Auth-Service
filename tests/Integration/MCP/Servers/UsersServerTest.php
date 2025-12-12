<?php

declare(strict_types=1);

namespace Tests\Integration\MCP;

use App\Mcp\Servers\UsersServer;
use App\Mcp\Tools\UsersTool;
use Database\Factories\UserFactory;

test('Test MCP UsersTool returns user email', function (): void {
    // Create a test user with a known name
    UserFactory::new()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
    ]);

    $response = UsersServer::tool(UsersTool::class, [
        'name' => 'John',
    ]);

    $response
        ->assertOk()
        ->assertSee('john.doe@example.com');
});

test('Test MCP UsersTool returns not found for non-existent user', function (): void {
    $response = UsersServer::tool(UsersTool::class, [
        'name' => 'NonExistentUser',
    ]);

    $response
        ->assertOk()
        ->assertSee('User not found');
});
