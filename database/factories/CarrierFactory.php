<?php

namespace Database\Factories;

use App\Models\Carrier;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Carrier::NAME => $this->faker->company(),
        ];
    }
}
