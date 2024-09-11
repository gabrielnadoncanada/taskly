<?php

namespace Database\Factories;

use App\Models\Client;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Client::NAME => $this->faker->name,
            Client::EMAIL => $this->faker->unique()->safeEmail,
            Client::PHONE => $this->faker->phoneNumber,
        ];
    }
}
