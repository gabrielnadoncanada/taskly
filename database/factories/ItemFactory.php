<?php

namespace Database\Factories;

use App\Models\Item;
use Bezhanov\Faker\ProviderCollectionHelper;
use Database\Factories\Concerns\CanCreateImages;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    use CanCreateImages;

    protected $model = Item::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Item::TITLE => $this->faker->productName,
            Item::DESCRIPTION => $this->faker->paragraph(),
            Item::MEDIA => $this->createImage(),
            Item::SKU => $this->faker->unique()->uuid,
            Item::DEFAULT_PRICE => $this->faker->randomFloat(2, 100, 10000),
            Item::CATEGORY_ID => null, //To fill in seeder
            Item::WEIGHT => $this->faker->numberBetween(1, 100),
        ];
    }
}
