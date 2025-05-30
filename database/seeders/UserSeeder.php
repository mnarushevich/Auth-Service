<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        UserFactory::new()
            ->count(10)
            ->withAddress()
            ->withUserRole()
            ->create();
    }
}
