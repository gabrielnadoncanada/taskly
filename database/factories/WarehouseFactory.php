<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Warehouse::NAME => $this->faker->company,
        ];
    }
}
