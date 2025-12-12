<?php

declare(strict_types=1);

namespace App\Enums;

enum AppRouteNamesEnum: string
{
    case USER_INFO_ROUTE_NAME = 'api.auth.me';
    case VERIFY_TOKEN_ROUTE_NAME = 'api.auth.verify';
    case LOGIN_ROUTE_NAME = 'api.auth.login';
    case LOGOUT_ROUTE_NAME = 'api.auth.logout';
    case REFRESH_TOKEN_ROUTE_NAME = 'api.auth.refresh';
    case GET_USER_BY_UUID_ROUTE_NAME = 'api.users.show';
    case UPDATE_USER_BY_UUID_ROUTE_NAME = 'api.users.update';
    case GET_USERS_ROUTE_NAME = 'api.users.index';
    case DELETE_USER_BY_UUID_ROUTE_NAME = 'api.users.destroy';
    case CREATE_USER_ROUTE_NAME = 'api.users.store';
    case ASSIGN_USER_ROLE_ROUTE_NAME = 'api.users.assign-user-role';
    case REMOVE_USER_ROLE_ROUTE_NAME = 'api.users.remove-user-role';
    case PASSWORD_SEND_RESET_LINK_ROUTE_NAME = 'api.password.send-reset-link';
    case PASSWORD_RESET_ROUTE_NAME = 'api.password.reset';
    case GET_ROLES_ROUTE_NAME = 'api.roles.index';
    case DELETE_ROLES_ROUTE_NAME = 'api.roles.destroy';
    case CREATE_ROLE_ROUTE_NAME = 'api.roles.store';
    case GET_PERMISSIONS_ROUTE_NAME = 'api.permissions.index';
    case DELETE_PERMISSIONS_ROUTE_NAME = 'api.permissions.destroy';
    case CREATE_PERMISSION_ROUTE_NAME = 'api.permissions.store';
    case MCP_USERS_SERVER_ROUTE_NAME = 'mcp.users';
}
