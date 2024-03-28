<?php

namespace Database\Factories;

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
            'last_name' => $this->faker->name,
            'first_name' => $this->faker->firstName,
            // 'image' => $this->faker->image('public/storage/images', 16, 16, null, false, false), // Too slow to generate
            'image' => $this->faker->imageUrl(16, 16, 'cats'),                                      // No file, but faster to generate
            'status' => $this->faker->randomElement(['inactive', 'pending', 'active']),
        ];
    }
}
