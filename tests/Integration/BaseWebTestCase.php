<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\TestCase;

abstract class BaseWebTestCase extends TestCase
{
    public const string USER_INFO_ROUTE_NAME = 'api.auth.me';

    public const string VERIFY_TOKEN_ROUTE_NAME = 'api.auth.verify';

    public const string LOGIN_ROUTE_NAME = 'api.auth.login';

    public const string LOGOUT_ROUTE_NAME = 'api.auth.logout';

    public const string REFRESH_TOKEN_ROUTE_NAME = 'api.auth.refresh';

    public const string GET_USER_BY_UUID_ROUTE_NAME = 'api.users.show';

    public const string UPDATE_USER_BY_UUID_ROUTE_NAME = 'api.users.update';

    public const string GET_USERS_ROUTE_NAME = 'api.users.index';

    public const string DELETE_USER_BY_UUID_ROUTE_NAME = 'api.users.destroy';

    public const string CREATE_USER_ROUTE_NAME = 'api.users.store';

    public const string ASSIGN_USER_ROLE_ROUTE_NAME = 'api.users.assign-user-role';

    public const string REMOVE_USER_ROLE_ROUTE_NAME = 'api.users.remove-user-role';

    public const string PASSWORD_SEND_RESET_LINK_ROUTE_NAME = 'api.password.send-reset-link';

    public const string PASSWORD_RESET_ROUTE_NAME = 'api.password.reset';

    public const string GET_ROLES_ROUTE_NAME = 'api.roles.index';

    public const string DELETE_ROLES_ROUTE_NAME = 'api.roles.destroy';

    public const string CREATE_ROLE_ROUTE_NAME = 'api.roles.store';

    public const string GET_PERMISSIONS_ROUTE_NAME = 'api.permissions.index';

    public const string DELETE_PERMISSIONS_ROUTE_NAME = 'api.permissions.destroy';

    public const string CREATE_PERMISSION_ROUTE_NAME = 'api.permissions.store';
}
