<?php

declare(strict_types=1);

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Resource;

class UsersApiDocResource extends Resource
{
    /**
     * The resource's description.
     */
    protected string $description = <<<'MARKDOWN'
    This resource is used to get the API documentation for the users.
    You can use the following tools to get information about the users:
    - UsersTool: Get information about a user by their name.
    - UsersApiDocResource: Get the API documentation for the users.
    MARKDOWN;

    /**
     * The resource's name.
     */
    protected string $name = 'get-users-api-docs';

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $docsPath = storage_path('api-docs/api-docs.json');

        if (file_exists($docsPath)) {
            $json = file_get_contents($docsPath);

            return Response::text($json);
        }

        return Response::text('API documentation for the users. Please run `php artisan l5-swagger:generate` to generate the full OpenAPI documentation.');
    }
}
