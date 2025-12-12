<?php

use App\Mcp\Servers\UsersServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/users', UsersServer::class)
    ->name('mcp.users');
// ->middleware(['throttle:mcp']);
