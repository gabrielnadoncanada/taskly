<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Customer;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Contact::NAME => $this->faker->name,
            Contact::EMAIL => $this->faker->email,
            Contact::CELL_PHONE => $this->faker->phoneNumber,
            Contact::FAX => $this->faker->phoneNumber,
            Contact::PREFER_SEND_MODE => $this->faker->word,
            Contact::EXTENSION => $this->faker->numerify('#####'),
            Contact::PHONE => $this->faker->phoneNumber,
        ];
    }

    public function forCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                Contact::CONTACTABLE_ID => Customer::factory(),
                Contact::CONTACTABLE_TYPE => Customer::class,
            ];
        });
    }
}
