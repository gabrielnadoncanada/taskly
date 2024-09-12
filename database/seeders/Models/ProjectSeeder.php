<?php

namespace Database\Seeders\Models;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            $this->createProjectsForOrganization($organization);
        });
    }

    private function createProjectsForOrganization($organization): void
    {
        Project::factory()
            ->count(20)
            ->has(
                Task::factory()->count(rand(2, 8))
                    ->state([
                        Task::ORGANIZATION_ID => $organization->id,
                    ])

                    ->afterCreating(function (Task $task) use ($organization) {
                        // Assign users and items to the task
                        $this->assignUsersAndItemsToTask($task, $organization);

                        // Randomly assign some tasks as parents to others
                        $taskChildrenCount = rand(0, 3); // Random number of children
                        if ($taskChildrenCount > 0) {
                            Task::factory()
                                ->count($taskChildrenCount)
                                ->state([
                                    'parent_task_id' => $task->id, // Set parent task
                                    Task::PROJECT_ID => $task->project->id, // Set parent task
                                    Task::ORGANIZATION_ID => $organization->id,
                                ])
                                ->afterCreating(function (Task $childTask) use ($organization) {
                                    $this->assignUsersAndItemsToTask($childTask, $organization);
                                })
                                ->create();
                        }
                    })
            )
            ->create([
                Project::ORGANIZATION_ID => $organization->id,
                Project::CLIENT_ID => $organization->clients->random()->id,
            ]);
    }

    private function assignUsersAndItemsToTask(Task $task, $organization): void
    {
        $users = $organization->users()->inRandomOrder()->take(rand(1, 1))->get();
        $items = $organization->items()->inRandomOrder()->take(array_rand([0, 0, 0, 1]))->get();

        foreach ($users as $user) {
            $task->users()->attach($user->id);
        }

        foreach ($items as $item) {
            $task->items()->attach($item->id, [
                'quantity' => rand(1, 2),
            ]);
        }
    }
}
