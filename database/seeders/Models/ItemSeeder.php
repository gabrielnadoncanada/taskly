<?php

namespace Database\Seeders\Models;

use App\Models\Item;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $organizations = Organization::all();

        $organizations->each(function ($organization) {
            Item::factory(40)->create()->each(function ($asset) use ($organization) {
                $category = $organization->categories->random();
                $asset->update([
                    Item::CATEGORY_ID => rand(0, 1) ? $category->id : null,
                    Item::ORGANIZATION_ID => $organization->id,
                ]);
            });
        });
    }
}
