<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->date('date');
            $table->foreignId('client_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->string('reference')->nullable();
            $table->string('method')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
