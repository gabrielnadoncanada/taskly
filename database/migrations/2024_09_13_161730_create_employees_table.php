<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position');
            $table->text('description')->nullable();
            $table->decimal('salary', 10)->nullable();
            $table->date('hired_at')->nullable();
            $table->softDeletes();
            $table->foreignId(config('filament-tenant.relation_foreign_key'))->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
};
