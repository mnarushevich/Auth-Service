<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\RolesEnum;
use App\Models\User as UserModel;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use LogicException;

class UserCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->formSchema()->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('first_name')->required(),
                TextInput::make('last_name')->required(),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->unique(table: UserModel::class)
                    ->required(),
                TextInput::make('password')->password()->required(),
                TextInput::make('country'),
                TextInput::make('phone'),
                Radio::make('role')
                    ->options([
                        RolesEnum::USER->value => 'User',
                        RolesEnum::ADMIN->value => 'Admin',
                    ])
                    ->label('User role')
                    ->inline()
                    ->default(RolesEnum::USER->value)
                    ->enum(RolesEnum::class),
            ])
            ->statePath('data')
            ->model(UserModel::class);
    }

    public function create(): void
    {
        $data = $this->formSchema()->getState();
        $data['password'] = Hash::make($data['password']);
        $user = UserModel::create($data);

        $this->redirectRoute('users.show', ['user' => $user]);
    }

    private function formSchema(): Schema
    {
        $schema = $this->getSchema('form');

        if (! $schema instanceof Schema) {
            throw new LogicException('The user create form schema is not registered.');
        }

        return $schema;
    }

    public function render(): View
    {
        return view('livewire.users.create');
    }
}
