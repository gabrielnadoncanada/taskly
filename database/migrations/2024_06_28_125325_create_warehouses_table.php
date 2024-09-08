<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_number');
            $table->string('name');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->unique(['warehouse_number', 'organization_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
