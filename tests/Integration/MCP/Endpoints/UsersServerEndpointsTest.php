<?php

declare(strict_types=1);

namespace Tests\Integration\Mcp\Endpoints;

use App\Enums\AppRouteNamesEnum;

describe('MCP Users Server', function (): void {
    it('returns the response for the get-user-by-name tool', function (): void {
        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'tools/call',
            'params' => [
                'name' => 'get-user-by-name',
                'arguments' => ['name' => 'John'],
            ],
            'id' => 1,
        ];
        $response = $this->postJson(
            getUrl(AppRouteNamesEnum::MCP_USERS_SERVER_ROUTE_NAME->value),
            $payload,
        );

        $response->assertOk();

        $data = $response->json();

        expect($data)->toHaveKey('jsonrpc', '2.0')
            ->and($data)->toHaveKey('id', 1)
            ->and($data)->toHaveKey('result')
            ->and($data['result'])->toHaveKey('content')
            ->and($data['result']['content'][0]['text'])->toContain($this->user->email)
            ->and($data['result']['content'][0]['text'])->toContain($this->user->phone)
            ->and($data['result']['content'][0]['text'])->toContain($this->user->first_name)
            ->and($data['result']['content'][0]['text'])->toContain($this->user->last_name);
    });

    it('returns the response for the users-api-doc-resource resource', function (): void {
        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'resources/read',
            'params' => [
                'uri' => 'file://resources/users-api-doc-resource',
            ],
            'id' => 1,
        ];
        $response = $this->postJson(
            getUrl(AppRouteNamesEnum::MCP_USERS_SERVER_ROUTE_NAME->value),
            $payload,
        );

        $response->assertOk();

        $data = $response->json();

        expect($data)->toHaveKey('jsonrpc', '2.0')
            ->and($data)->toHaveKey('id', 1)
            ->and($data)->toHaveKey('result')
            ->and($data['result'])->toHaveKey('contents')
            ->and($data['result']['contents'][0]['text'])->toContain('Auth Service API');
    });
});
