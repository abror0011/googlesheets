<?php

namespace Database\Factories;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataItem>
 */
class DataItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement([StatusEnum::PROHIBITED->value, StatusEnum::ALLOWED->value]),
        ];
    }
}
