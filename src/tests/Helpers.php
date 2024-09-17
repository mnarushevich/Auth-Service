<?php

declare(strict_types=1);

use Illuminate\Support\Facades\URL;

function getUrl(string $route): string
{
    return URL::route($route);
}
