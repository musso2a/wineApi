<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wine>
 */
class WineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'year' => $this->faker->year,
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'condition' => $this->faker->randomDigit(),
            'description' => $this->faker->sentence(15),
            'images' => $this->faker->imageUrl(640, 480),
            'color' => $this->faker->colorName,
            'trade' => $this->faker->text(255),
            'provenance' => $this->faker->text(255),
            'user_id' => User::query()->inRandomOrder()->first()->id ?? 1,
        ];
    }
}
