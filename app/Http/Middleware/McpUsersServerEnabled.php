<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use Symfony\Component\HttpFoundation\Response;

class McpUsersServerEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check the feature for null scope (global feature)
        if (! Feature::for(null)->active('mcp-users-server')) {
            return response()->json(['message' => 'MCP users server is not enabled'], 404);
        }

        return $next($request);
    }
}
