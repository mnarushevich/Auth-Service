<?php

namespace App\Livewire\Users;

use App\Models\User as UserModel;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Notifications\Notification;

class User extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public UserModel $user;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('User deleted')
                    ->body('The user has been deleted successfully.'),
            )
            ->action(function (array $arguments) {
                $user = UserModel::find($arguments['user'])->first();
                $user?->delete();

                return redirect()->route('users.index');
            })
            ->extraAttributes([
                'class' => 'py-2.5 px-6 text-sm bg-green-50 text-green-500 rounded-full cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100',
            ]);
    }

    public function mount(UserModel $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.user');
    }
}
