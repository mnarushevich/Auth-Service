<?php

namespace App\Livewire\Users;

use App\Models\User as UserModel;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
    use UserActionsTrait;

    public function table(Table $table): Table
    {
        return $table
            ->query(UserModel::query())
            ->header(view('livewire.users.layouts.table-header'))
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
                    ->url(fn(UserModel $user): string => route('users.show', $user))
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
                TextColumn::make('created_at')->since(),
            ])
            ->actions([
                $this->processEditActionButton(
                    EditAction::make()->url(fn(UserModel $user): string => route('users.edit', $user)),
                ),
                $this->getDeleteActionButton(),
            ]);
    }

    public function render()
    {
        return view('livewire.users.users-list');
    }
}
