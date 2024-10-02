<?php

namespace App\Livewire\Users;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class UsersList extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('full_name')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('last_name', $direction)
                            ->orderBy('first_name', $direction);
                    })->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->where('first_name', 'like', "%$search%")
                                ->orWhere('last_name', 'like', "%$search%");
                        },
                        isIndividual: true,
                    )
                    ->url(fn (User $user): string => route('users.show', $user))
                    ->tooltip('Click to view details'),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('country')->sortable(),
                TextColumn::make('phone'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (User $user): string => route('users.edit', $user))
                    ->extraAttributes(['wire:navigate' => 'true']),
                DeleteAction::make(),
            ]);
    }

    public function render()
    {
        return view('livewire.users.users-list');
    }
}
