<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Models\TaskStatus;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TaskStatuses::all() as $status) {
            TaskStatus::create($status);
        }
    }
}