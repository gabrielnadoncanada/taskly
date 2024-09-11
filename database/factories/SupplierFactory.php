<?php

namespace Database\Factories;

use App\Models\Supplier;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Supplier::TITLE => $this->faker->company(),
            Supplier::EMAIL => $this->faker->unique()->safeEmail,
            Supplier::PHONE => $this->faker->phoneNumber,
        ];
    }
}
