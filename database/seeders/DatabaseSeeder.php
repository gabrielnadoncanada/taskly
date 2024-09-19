<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Appel aux différents seeders et génération de permissions avec Shield
        $this->call([
            ModelSeeder::class,
            PermissionSeeder::class,
        ]);

        // Générer les permissions avec le package Shield
        Artisan::call('shield:generate --all');
    }
}
