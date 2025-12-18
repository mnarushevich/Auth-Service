<?php

declare(strict_types=1);

namespace Tests\Integration\MCP;

use App\Mcp\Resources\UsersApiDocResource;
use App\Mcp\Servers\UsersServer;
use App\Mcp\Tools\UsersTool;

test('Test MCP UsersTool returns user email', function (): void {
    $response = UsersServer::tool(UsersTool::class, [
        'name' => $this->user->first_name,
    ]);

    $response
        ->assertOk()
        ->assertSee($this->user->email)
        ->assertSee($this->user->first_name)
        ->assertSee($this->user->last_name)
        ->assertSee($this->user->phone);
});

test('Test MCP UsersTool returns not found for non-existent user', function (): void {
    $response = UsersServer::tool(UsersTool::class, [
        'name' => 'NonExistentUser',
    ]);

    $response
        ->assertOk()
        ->assertSee('User not found');
});

test('Test MCP UsersApiDocResource returns API documentation', function (): void {
    $response = UsersServer::resource(UsersApiDocResource::class);

    $response
        ->assertOk()
        ->assertSee('Auth Service API')
        ->assertSee('openapi');
});
