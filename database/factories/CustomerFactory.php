<?php

namespace Database\Factories;

use App\Models\Customer;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Customer::NAME => $this->faker->name,
        ];
    }
}
