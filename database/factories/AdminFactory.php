<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            return [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('00000000'), // hashed password
        'email_verified_at' => now(),
    ];
    }
}
