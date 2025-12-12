<?php

use App\Mcp\Servers\UsersServer;
use Laravel\Mcp\Facades\Mcp;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

Mcp::web('/mcp/users', UsersServer::class)
    ->name('mcp.users')
    ->middleware(EnsureFeaturesAreActive::using('mcp-users-server'));
