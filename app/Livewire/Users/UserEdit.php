<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\RolesEnum;
use App\Models\User;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use LogicException;

class UserEdit extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public User $user;

    public function mount(): void
    {
        $this->formSchema()->fill($this->user->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('first_name')->filled(),
                TextInput::make('last_name')->filled(),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignorable: $this->user)
                    ->filled(),
                TextInput::make('country'),
                TextInput::make('phone'),
                Radio::make('role')
                    ->options([
                        RolesEnum::USER->value => 'User',
                        RolesEnum::ADMIN->value => 'Admin',
                    ])
                    ->label('User role')
                    ->inline()
                    ->enum(RolesEnum::class),
            ])
            ->statePath('data')
            ->model($this->user);
    }

    public function save(): void
    {
        $data = $this->formSchema()->getState();

        $this->user->update($data);

        $this->redirectRoute('users.show', ['user' => $this->user]);
    }

    private function formSchema(): Schema
    {
        $schema = $this->getSchema('form');

        if (! $schema instanceof Schema) {
            throw new LogicException('The user edit form schema is not registered.');
        }

        return $schema;
    }

    public function render(): View
    {
        return view('livewire.users.edit');
    }
}
