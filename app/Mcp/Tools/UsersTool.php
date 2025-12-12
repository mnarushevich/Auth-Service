<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Models\User;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsIdempotent]
#[IsReadOnly]
class UsersTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        This tool is used to get the user by their name.
    MARKDOWN;

    protected string $name = 'get-user-by-name';

    protected string $title = 'Get User by Name';

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $user = User::where('first_name', $validated['name'])
            ->orWhere('last_name', $validated['name'])
            ->first();

        if (! $user) {
            return Response::text('User not found');
        }

        return Response::json([
            'email' => $user->email,
            'phone' => $user->phone,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('The name of the user')
                ->required(),
        ];
    }

    /**
     * Get the tool's output schema.
     *
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function outputSchema(JsonSchema $schema): array
    {
        return [
            'email' => $schema->string()
                ->description('The email of the user')
                ->required(),
            'phone' => $schema->string()
                ->description('The phone of the user')
                ->required(),
            'first_name' => $schema->string()
                ->description('The first name of the user')
                ->required(),
            'last_name' => $schema->string()
                ->description('The last name of the user')
                ->required(),
        ];
    }
}
