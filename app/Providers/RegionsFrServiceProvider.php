<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Squire\Models\Region;
use Squire\Repository;

class RegionsFrServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Repository::registerSource(Region::class, 'fr', __DIR__.'/../../resources/squire-data/regions-fr.csv');
    }
}
