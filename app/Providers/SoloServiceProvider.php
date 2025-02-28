<?php

declare(strict_types=1);

namespace App\Providers;

use AaronFrancis\Solo\Commands\EnhancedTailCommand;
use AaronFrancis\Solo\Facades\Solo;
use Illuminate\Support\ServiceProvider;

class SoloServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Solo may not (should not!) exist in prod, so we have to
        // check here first to see if it's installed.
        if (class_exists('\AaronFrancis\Solo\Manager')) {
            $this->configure();
        }
    }

    public function configure(): void
    {
        Solo::useTheme('dark')
            // Commands that auto start.
            ->addCommands([
                EnhancedTailCommand::make('Logs', 'tail -f -n 100 '.storage_path('logs/laravel.log')),
                'Vite' => 'npm run dev',
                'Pail' => 'php artisan pail',
                // 'HTTP' => 'php artisan serve',
                //'Start Web UI' => 'npm run dev',
                'About' => 'php artisan solo:about',
            ])
            // Not auto-started
            ->addLazyCommands([
                'Queue' => 'php artisan queue:listen --tries=1',
                // 'Reverb' => 'php artisan reverb:start',
                'Pint' => './vendor/bin/pint --ansi',
                'Tests' => 'php artisan test --colors=always --env=testing',
            ]);
    }

    public function boot(): void
    {
        //
    }
}
