<?php

namespace Database\Factories;

use App\Models\InvitationTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    public function definition(): array
    {
        $primary = fake()->firstName();
        $secondary = fake()->firstName();
        $title = "Pernikahan {$primary} & {$secondary}";

        return [
            'user_id' => User::factory(),
            'template_id' => InvitationTemplate::query()->inRandomOrder()->value('id'),
            'title' => $title,
            'slug' => Str::slug($title.' '.fake()->unique()->numberBetween(100, 999)),
            'event_type' => 'wedding',
            'primary_name' => $primary,
            'secondary_name' => $secondary,
            'host_name' => 'Keluarga besar '.$primary.' dan '.$secondary,
            'event_date' => fake()->dateTimeBetween('+1 month', '+10 months')->format('Y-m-d'),
            'event_time' => '10:00',
            'timezone' => 'Asia/Jakarta',
            'venue_name' => fake()->company(),
            'venue_address' => fake()->address(),
            'description' => fake()->sentence(18),
            'status' => 'active',
            'theme_color' => '#a4785b',
            'theme_font' => 'Inter',
        ];
    }
}
