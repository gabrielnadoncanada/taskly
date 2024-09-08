<?php

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\MeasurementSystem;
use App\Models\Organization;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    public function definition()
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Organization::TITLE => $this->faker->company,
            Organization::CURRENCY => Currency::randomValue(),
            Organization::MEASUREMENT_SYSTEM => MeasurementSystem::randomValue(),
            Organization::EMAIL => $this->faker->unique()->companyEmail,
        ];
    }
}
