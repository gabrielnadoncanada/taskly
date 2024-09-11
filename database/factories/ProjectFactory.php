<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Bezhanov\Faker\ProviderCollectionHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        ProviderCollectionHelper::addAllProvidersTo($this->faker);

        return [
            Project::TITLE => $this->faker->company(),
            Project::DATE =>  $this->faker->dateTimeBetween('+1 months', '+5 months'),
            Project::DESCRIPTION => $this->faker->paragraph(),
            Project::STATUS => ProjectStatus::NEW,
        ];
    }
}
