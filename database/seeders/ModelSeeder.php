<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Item;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        $tenants->each(function (Tenant $tenant) {
            $this->seedTenantData($tenant);
        });
    }

    private function seedTenantData(Tenant $tenant): void
    {
        $users = User::factory()->count(5)->create();
        $tenant->users()->attach($users);

        $items = Item::factory()->count(40)->create(['tenant_id' => $tenant->id]);

        Client::factory()
            ->count(10)
            ->hasAddresses(3)
            ->has(
                Project::factory()
                    ->count(4)
                    ->state(['tenant_id' => $tenant->id])
                    ->has(
                        Task::factory()
                            ->count(rand(2, 8))
                            ->afterCreating(function (Task $task) use ($users, $items) {
                                $this->assignUsersAndItemsToTask($task, $users, $items);
                                $this->createChildTasks($task, $users, $items);
                            })
                    )
            )
            ->create(['tenant_id' => $tenant->id]);
    }

    private function assignUsersAndItemsToTask(Task $task, $users, $items): void
    {
        $assignedUsers = $users->random(1);
        $assignedItems = $items->random(rand(0, 1));

        $task->users()->attach($assignedUsers);
        if ($assignedItems->isNotEmpty()) {
            $task->items()->attach($assignedItems, ['quantity' => rand(1, 2)]);
        }
    }

    private function createChildTasks(Task $task, $users, $items): void
    {
        $childTasksCount = rand(0, 3);
        if ($childTasksCount > 0) {
            Task::factory()
                ->count($childTasksCount)
                ->state([
                    'parent_task_id' => $task->id,
                    'project_id' => $task->project_id,
                ])
                ->afterCreating(function (Task $childTask) use ($users, $items) {
                    $this->assignUsersAndItemsToTask($childTask, $users, $items);
                })
                ->create();
        }
    }
}
