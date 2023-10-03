<?php

namespace Database\Factories;

use App\Models\Chirp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chirp>
 */
class ChirpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $created_at = $this->faker->dateTimeBetween('-2 months');

        return [
            'message' => $this->faker->sentence,
            'created_at' => /* somewhere in the past two months */ $created_at,
            'updated_at' => $this->faker->boolean() ? $this->faker->dateTimeBetween($created_at) : null,
        ];
    }
}
