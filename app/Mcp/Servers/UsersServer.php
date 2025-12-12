<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Resources\UsersApiDocResource;
use App\Mcp\Tools\UsersTool;
use Laravel\Mcp\Server;

class UsersServer extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Users Server';

    /**
     * The MCP server's version.
     */
    protected string $version = '0.0.1';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        Instructions describing the server and its features.
        You can use the following tools to get information about the users:
        - UsersTool: Get information about a user by their name.
        - UsersApiDocResource: Get the API documentation for the users.
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        UsersTool::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        UsersApiDocResource::class,
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        //
    ];
}
