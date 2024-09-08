<?php

namespace Database\Factories;

use App\Enums\DimensionUnits;
use App\Enums\ItemStatus;
use App\Enums\WeightUnits;
use App\Models\Item;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Item::STATUS => ItemStatus::randomValue(),
            Item::DESCRIPTION => $this->faker->productName,
            Item::WEIGHT => $this->faker->randomFloat(2, 1, 100),
            Item::WEIGHT_UNIT => WeightUnits::KG->value,
            Item::WIDTH => $this->faker->randomFloat(2, 1, 100),
            Item::LENGTH => $this->faker->randomFloat(2, 1, 100),
            Item::HEIGHT => $this->faker->randomFloat(2, 1, 100),
            Item::DIMENSION_UNIT => DimensionUnits::CM->value,
        ];
    }
}
