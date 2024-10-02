<?php

namespace App\Livewire\Users;

use App\Models\User as UserModel;
use Livewire\Component;

class User extends Component
{
    public UserModel $user;

    public function mount(UserModel $user)
    {
        $this->user = $user;
    }
}
