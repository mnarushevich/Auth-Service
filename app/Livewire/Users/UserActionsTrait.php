<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

trait UserActionsTrait
{
    public function getCreateActionButton(): Action
    {
        return Action::make('create')
            ->icon('heroicon-m-plus-circle')
            ->button()
            ->url(route('users.create'))
            ->extraAttributes(['wire:navigate' => 'true'])
            ->labeledFrom('md');
    }

    public function processEditActionButton(EditAction $editAction): Action
    {
        return $editAction->extraAttributes(['wire:navigate' => 'true']);
    }

    public function getDeleteActionButton(): Action
    {
        return DeleteAction::make();
    }
}
