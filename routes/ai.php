<?php

use App\Mcp\Servers\UsersServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/users', UsersServer::class);
// ->middleware(['throttle:mcp']);
