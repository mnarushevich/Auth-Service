<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'street_address' => $this->faker->address(),
            'address_line_2' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->word(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
