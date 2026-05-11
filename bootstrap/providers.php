<?php

use App\Providers\AppServiceProvider;
use Bugsnag\BugsnagLaravel\BugsnagServiceProvider;

return [
    AppServiceProvider::class,
    BugsnagServiceProvider::class,
];
