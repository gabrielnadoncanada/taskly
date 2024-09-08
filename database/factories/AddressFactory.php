<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Squire\Models\Region;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);
        $countryCode = 'ca';

        return [
            Address::STREET => $this->faker->streetAddress,
            Address::CITY => $this->faker->city(),
            Address::STATE => Region::where('country_id', $countryCode)->inRandomOrder()->first()->id,
            Address::COUNTRY => $countryCode,
            Address::POSTAL_CODE => $this->getCanadianPostalCode(),
        ];
    }

    public function getCanadianPostalCode()
    {
        return strtoupper($this->faker->bothify('?#?#?#'));
    }

    public function forCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                Address::ADDRESSABLE_ID => Customer::factory(),
                Address::ADDRESSABLE_TYPE => Customer::class,
            ];
        });
    }
}
