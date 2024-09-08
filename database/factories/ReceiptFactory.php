<?php

namespace Database\Factories;

use App\Enums\ReceiptStatus;
use App\Models\Receipt;
use Bezhanov\Faker\ProviderCollectionHelper;
use Database\Factories\Traits\Addressable;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    use Addressable;

    protected $model = Receipt::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        $randomDate = $this->faker->dateTimeBetween('-1 months', '+2 months');

        $receiptDate = (clone $randomDate)->modify('-'.$this->faker->numberBetween(1, 30).' days');

        return [
            Receipt::DATE => $randomDate,
            Receipt::PURCHASE_ORDER_IDENTIFIER => $this->faker->unique()->numberBetween(100000, 999999),
            Receipt::STATUS => ReceiptStatus::randomValue(),
            Receipt::EXPECTED_DATE => rand(1, 3) === 1 ? $randomDate : $receiptDate,
        ];
    }
}
