<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'review' => $this->faker->realText(),
            'service_rating' => $this->faker->numberBetween(1, 5),
            'quality_rating' => $this->faker->numberBetween(1, 5),
            'cleanliness_rating' => $this->faker->numberBetween(1, 5),
            'price_rating' => $this->faker->numberBetween(1, 5),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'yesterday'),
        ];
    }
}
