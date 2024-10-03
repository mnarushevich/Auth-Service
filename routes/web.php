<?php

use App\Livewire\Users\User;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UsersList;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'users.'], function () {
    Route::get('/users/create', UserCreate::class)->name('create');
    Route::get('/users', UsersList::class)->name('index');
    Route::get('/users/{user}', User::class)->name('show');
    Route::get('/users/{user}/edit', UserEdit::class)->name('edit');
});
Route::redirect('/', '/users', 301);
