<?php

use App\Enums\Currency;
use App\Enums\MeasurementSystem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('currency')->default(Currency::CAD);
            $table->string('measurement_system')->default(MeasurementSystem::METRIC);
            $table->string('email')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizations');
    }
};
