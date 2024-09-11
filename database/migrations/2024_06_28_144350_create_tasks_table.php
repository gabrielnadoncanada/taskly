<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('date')->default(now());
            $table->boolean('all_day')->default(true);
            $table->integer('order')->default(0)->index();
            $table->unsignedBigInteger('parent_task_id')->nullable();

            $table->integer('estimated_time')->nullable();
            $table->integer('actual_time')->nullable();
            $table->string('status')->default(\App\Enums\TaskStatus::NOT_STARTED);
            $table->foreignId('project_id')->constrained();
            $table->foreign('parent_task_id')->references('id')->on('tasks');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }
};
