<?php

namespace Database\Factories;

use App\Domain\Model\Curso;
use App\Domain\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Model\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

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
        $curso = Curso::inRandomOrder()->first();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'id_curso' => $curso->id,
            'password' => static::$password ??= Hash::make('password'),
            'biografia' => $this->faker->sentence(6),
            'ano_inicio_curso' => $this->faker->numberBetween(2018, 2022),
            'ano_fim_curso' => $this->faker->numberBetween(2023, 2026),
            'situacao' => 'A',
            'remember_token' => Str::random(10),
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
}
