<?php

namespace Database\Seeders\Models;

use App\Models\Category;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $organizations = Organization::all();

        $organizations->each(function ($organization) {
            Category::factory(10)->create([Category::ORGANIZATION_ID => $organization->id]);
        });
    }
}
