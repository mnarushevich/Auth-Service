<?php

declare(strict_types=1);

namespace App\Providers;

use App\Features\McpUsersServer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enable globally the feature for the MCP users server
        Feature::define('mcp-users-server', McpUsersServer::class);

        if ($this->app->isProduction()) {
            DB::prohibitDestructiveCommands();
        }
    }
}
