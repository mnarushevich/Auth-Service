<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\TestCase;

abstract class BaseWebTestCase extends TestCase
{
    public const string USER_INFO_ROUTE_NAME = 'auth.me';

    public const string LOGIN_ROUTE_NAME = 'auth.login';

    public const string LOGOUT_ROUTE_NAME = 'auth.logout';

    public const string REFRESH_TOKEN_ROUTE_NAME = 'auth.refresh';

    public const string GET_USER_BY_UUID_ROUTE_NAME = 'users.show';

    public const string UPDATE_USER_BY_UUID_ROUTE_NAME = 'users.update';

    public const string GET_USERS_ROUTE_NAME = 'users.index';

    public const string DELETE_USER_BY_UUID_ROUTE_NAME = 'users.destroy';

    public const string CREATE_USER_ROUTE_NAME = 'users.store';
}
