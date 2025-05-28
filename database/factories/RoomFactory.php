<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RoomCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_number' => $this->faker->unique()->numerify('R###'),
            'room_floor' => $this->faker->numberBetween(1, 10),
            'room_type' => $this->faker->randomElement(['single', 'double', 'suite']),
            'room_status' => $this->faker->randomElement(['available', 'occupied', 'maintenance']),
            'room_description' => $this->faker->optional()->sentence(),
            'room_category_id' => RoomCategory::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
