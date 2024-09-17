<?php

declare(strict_types=1);

namespace tests\Integration;

use Tests\TestCase;

abstract class BaseWebTestCase extends TestCase
{
    public const string USER_INFO_ROUTE_NAME = 'auth.me';

    public const string LOGIN_ROUTE_NAME = 'auth.login';

    public const string LOGOUT_ROUTE_NAME = 'auth.logout';

    public const string REFRESH_TOKEN_ROUTE_NAME = 'auth.refresh';
}
