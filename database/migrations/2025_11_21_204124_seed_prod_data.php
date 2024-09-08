<?php

use Database\Seeders\ProdDataSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        (new ProdDataSeeder)->run();
    }
};
