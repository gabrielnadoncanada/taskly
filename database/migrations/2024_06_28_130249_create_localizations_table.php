<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('localizations', function (Blueprint $table) {
            $table->id();
            $table->string('location_identifier');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->unique(['location_identifier', 'warehouse_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
