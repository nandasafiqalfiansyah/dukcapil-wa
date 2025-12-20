<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\WhatsAppUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    protected $model = ServiceRequest::class;

    public function definition(): array
    {
        return [
            'whatsapp_user_id' => WhatsAppUser::factory(),
            'assigned_to' => null,
            'request_number' => ServiceRequest::generateRequestNumber(),
            'service_type' => $this->faker->randomElement(['ktp', 'kk', 'akta_lahir', 'akta_kematian', 'akta_nikah']),
            'description' => $this->faker->sentence(),
            'status' => 'pending',
            'priority' => 'normal',
            'escalated_at' => null,
            'notes' => null,
            'metadata' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function escalated(): static
    {
        return $this->state(fn (array $attributes) => [
            'escalated_at' => now(),
            'priority' => 'urgent',
        ]);
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => User::factory()->create(['role' => 'officer'])->id,
        ]);
    }
}
