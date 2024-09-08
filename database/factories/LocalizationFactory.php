<?php

// File: database/factories/LocalizationFactory.php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Localization;
use App\Models\Warehouse;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocalizationFactory extends Factory
{
    protected $model = Localization::class;

    protected static $locationNumber = 1;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);
        $warehouseIds = Warehouse::pluck('id')->toArray();

        return [
            Localization::WAREHOUSE_ID => $this->faker->randomElement($warehouseIds),
            Localization::LOCATION_IDENTIFIER => $this->faker->numerify('####-####'),
        ];
    }

    public function forWarehouse()
    {
        return $this->state(function (array $attributes) {
            return [
                Address::ADDRESSABLE_ID => Warehouse::factory(),
                Address::ADDRESSABLE_TYPE => Warehouse::class,
            ];
        });
    }
}
