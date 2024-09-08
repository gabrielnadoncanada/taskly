<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('extension')->nullable();
            $table->string('prefer_send_mode')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->morphs('contactable');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
