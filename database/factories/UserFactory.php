<?php

namespace Database\Factories;

use App\Infrastructure\User\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cases = UserStatus::cases();

        return [
            'full_name' => fake()->name(),
            'nick_name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Crypt::encryptString('Zaxscd123!'),
            'remember_token' => Str::random(10),
            'phone' => fake()->phoneNumber(),
            'status' => $cases[array_rand($cases)],
            'coins_silver' => fake()->randomFloat(2, 0, 1000),
            'coins_gold' => fake()->randomFloat(2, 0, 1000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
