<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcceptApplicationJsonHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader != 'application/json' && $request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
