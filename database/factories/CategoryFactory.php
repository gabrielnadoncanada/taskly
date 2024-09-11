<?php

namespace Database\Factories;

use App\Models\Category;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Category::TITLE => $this->faker->department,
            Category::DESCRIPTION => $this->faker->sentence,
            Category::COLOR => $this->faker->hexColor,
        ];
    }
}
