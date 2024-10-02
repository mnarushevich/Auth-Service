<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class UsersList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Collection $users;

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
                    ),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('country')->sortable(),
                TextColumn::make('phone'),
            ]);
//            ->filters([
//                // ...
//            ])
//            ->actions([
//                // ...
//            ])
//            ->bulkActions([
//                // ...
//            ]);
    }

//    public function mount(): void
//    {
//        $this->users = User::all();
//    }

    public function render()
    {
        return view('livewire.users.users-list');
    }
}
