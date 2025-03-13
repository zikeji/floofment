<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhoneRecording>
 */
class PhoneRecordingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sid' => 'CS'.fake()->md5(),
            'from' => fake()->e164PhoneNumber(),
            'called' => fake()->e164PhoneNumber(),
        ];
    }
}
