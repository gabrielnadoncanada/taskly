<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->integer('carrier_number');
            $table->string('name');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->unique(['carrier_number', 'organization_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
