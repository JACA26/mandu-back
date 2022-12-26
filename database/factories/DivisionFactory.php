<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\division>
 */
class DivisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => 'Division'. $this->faker->name,
            'nivel' => $this->faker->numberBetween(1, 10),
            'colaboradores' => $this->faker->numberBetween(0,20),
            'embajador' => $this->faker->name,
            'parent_id' => null,
        ];
    }
}
