<?php

namespace Database\Factories;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::factory(),
            'name' => fake()->name(),
            'whatsapp' => fake()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'category' => fake()->randomElement(array_keys(config('undi.guest_categories'))),
            'max_companions' => fake()->numberBetween(1, 4),
            'personal_token' => Str::random(32),
            'status' => 'draft',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
