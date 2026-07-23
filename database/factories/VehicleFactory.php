<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vin' => $this->faker->unique()->bothify('???????????????'),
            'model' => $this->faker->randomElement(['Renault Kangoo', 'Peugeot Partner', 'Citroën Berlingo', 'Ford Transit']),
            'owner_address' => $this->faker->sha256(),
        ];
    }
}
