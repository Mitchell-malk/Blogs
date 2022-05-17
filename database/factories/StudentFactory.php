<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'son' => $this->faker->unique()->numerify('20####'),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->freeEmail,
            'age' => $this->faker->numberBetween(18, 25),
            'dept' => $this->faker->randomElement(['CS', 'IS', 'MA']),
            'gender' => $this->faker->numberBetween(0, 1),
        ];
    }
}
