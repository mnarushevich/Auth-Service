<?php

namespace App\Livewire\Users;

use App\Models\User as UserModel;
use Livewire\Component;

class UserEdit extends Component
{
    public UserModel $user;

    public function mount(UserModel $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}
