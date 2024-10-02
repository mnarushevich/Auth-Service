<?php

use App\Livewire\Users\UsersList;
use Illuminate\Support\Facades\Route;

Route::get('/users', UsersList::class);
Route::redirect('/', '/users', 301);
