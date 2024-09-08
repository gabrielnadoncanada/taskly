<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->text('note')->nullable();

            $table->morphs('addressable');
            $table->timestamps();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');

            $table->softDeletes();
        });
    }
};
