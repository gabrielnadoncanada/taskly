<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            User::FIRST_NAME => $this->faker->firstName,
            User::LAST_NAME => $this->faker->lastName,
            User::NOTE => $this->faker->sentence,
            User::PHONE => $this->faker->phoneNumber,
            User::EMAIL => $this->faker->unique()->safeEmail,
            User::EMAIL_VERIFIED_AT => now(),
            User::PASSWORD => static::$password ??= Hash::make('password'),
            User::REMEMBER_TOKEN => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(fn () => [
            User::EMAIL_VERIFIED_AT => null,
        ]);
    }
}
