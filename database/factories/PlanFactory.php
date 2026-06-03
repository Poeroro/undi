<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(12),
            'price' => fake()->numberBetween(0, 500000),
            'currency' => 'IDR',
            'invitation_limit' => fake()->numberBetween(1, 20),
            'guest_limit' => fake()->numberBetween(50, 2000),
            'gallery_limit' => fake()->numberBetween(6, 120),
            'custom_music' => fake()->boolean(),
            'qr_code' => true,
            'rsvp' => true,
            'custom_domain' => fake()->boolean(),
            'active_days' => fake()->numberBetween(14, 365),
            'is_featured' => false,
            'is_active' => true,
        ];
    }
}
