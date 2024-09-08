<?php

namespace Database\Factories;

use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition()
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);
        $randomDate = $this->faker->dateTimeBetween('-1 months', '+5 months');

        $shipmentDate = (clone $randomDate)->modify('-'.$this->faker->numberBetween(1, 30).' days');

        return [
            Shipment::PURCHASE_ORDER_IDENTIFIER => $this->faker->unique()->numberBetween(100000, 999999),
            Shipment::ADDRESS_ID => null,
            Shipment::STATUS => ShipmentStatus::randomValue(),
            Shipment::DATE => now(),
            Shipment::EXPECTED_DATE => rand(1, 3) === 1 ? $randomDate : $shipmentDate,
        ];
    }
}
