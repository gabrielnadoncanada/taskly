<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            Task::TITLE => $this->faker->sentence,
            Task::DESCRIPTION => $this->faker->paragraph,
            Task::DATE =>  $this->faker->dateTimeBetween('now', '+3 months'),
            Task::ORDER => $this->faker->numberBetween(1, 10),
            Task::PARENT_TASK_ID => null,
            Task::ESTIMATED_TIME => $this->faker->randomFloat(2, 1, 100),
            Task::ACTUAL_TIME => $this->faker->optional()->randomFloat(2, 1, 100),
            Task::STATUS => TaskStatus::randomValue(),
        ];
    }

    public function withParentTask($parentTaskId)
    {
        return $this->state(function () use ($parentTaskId) {
            return ['parent_task_id' => $parentTaskId];
        });
    }
}
