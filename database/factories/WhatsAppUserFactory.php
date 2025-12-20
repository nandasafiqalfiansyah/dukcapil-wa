<?php

namespace Database\Factories;

use App\Models\WhatsAppUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WhatsAppUser>
 */
class WhatsAppUserFactory extends Factory
{
    protected $model = WhatsAppUser::class;

    public function definition(): array
    {
        return [
            'phone_number' => $this->faker->unique()->numerify('+62###########'),
            'name' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'),
            'is_verified' => false,
            'verified_at' => null,
            'status' => 'pending',
            'metadata' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'verified_at' => now(),
            'status' => 'active',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'blocked',
        ]);
    }
}
