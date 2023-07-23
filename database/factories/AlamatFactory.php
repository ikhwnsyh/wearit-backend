<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alamat>
 */
class AlamatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 3,
            'alamat' => fake()->address(),
            'province_id' => 11,
            'regency_id' => 1101,
            'district_id' => 1101010
        ];
    }
}
