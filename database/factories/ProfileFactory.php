<?php

namespace Database\Factories;

use App\Models\Administrator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'administrator_id' => Administrator::inRandomOrder()->first(),
            'last_name' => $this->faker->name,
            'first_name' => $this->faker->firstName,
            'image' => $this->faker->image('public/storage/images', 640, 480, null, false),
            'status' => $this->faker->randomElement(['inactive', 'pending', 'active']),
        ];
    }
}
