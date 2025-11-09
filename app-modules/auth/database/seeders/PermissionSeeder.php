<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Очищаем существующие разрешения (опционально)
        // Permission::query()->delete();

        // Все разрешения для модуля Core
        $permissions = [
            // Course Permissions
            'create courses',
            'update any courses',
            'update courses',
            'delete any courses',
            'delete courses',
            'restore any courses',
            'restore courses',
            'force delete courses',
            'enroll in courses',
            'manage course users',

            // Lesson Permissions
            'view any lessons',
            'view lessons',
            'create any lessons',
            'create lessons',
            'update any lessons',
            'update lessons',
            'delete any lessons',
            'delete lessons',
            'restore any lessons',
            'restore lessons',
            'force delete lessons',

            // Task Permissions
            'view any tasks',
            'view tasks',
            'create tasks',
            'update any tasks',
            'update tasks',
            'delete any tasks',
            'delete tasks',

            // UserCourse Permissions (отношения пользователь-курс)
            'view any user courses',

            // UserLesson Permissions (прогресс пользователя)
            'view any user lessons',
            'restore any user lessons',
            'delete any user lessons',
            'force delete user lessons',
        ];

        // Создаем все разрешения
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api'
            ]);
        }

        // Создаем роли и назначаем разрешения
        $this->createRoles();
    }

    private function createRoles(): void
    {
        // Роль Администратор - все разрешения
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);
        $admin->givePermissionTo(Permission::all());

        // Роль Преподаватель (Teacher)
        $teacher = Role::firstOrCreate([
            'name' => 'teacher',
            'guard_name' => 'api'
        ]);
        $teacher->givePermissionTo([
            // Course
            'create courses',
            'update courses',
            'delete courses',
            'restore courses',
            'manage course users',

            // Lesson
            'view any lessons',
            'view lessons',
            'create lessons',
            'update lessons',
            'delete lessons',
            'restore lessons',

            // Task
            'view any tasks',
            'view tasks',
            'create tasks',
            'update tasks',
            'delete tasks',
        ]);

        // Роль Студент (Student)
        $student = Role::firstOrCreate([
            'name' => 'student',
            'guard_name' => 'api'
        ]);
        $student->givePermissionTo([
            // Course
            'enroll in courses',

            // Lesson
            'view lessons',

            // Task
            'view tasks',
        ]);

        // Роль Модератор (Moderator)
        $moderator = Role::firstOrCreate([
            'name' => 'moderator',
            'guard_name' => 'api'
        ]);
        $moderator->givePermissionTo([
            // Course
            'update any courses',
            'delete any courses',
            'restore any courses',
            'manage course users',

            // Lesson
            'view any lessons',
            'update any lessons',
            'delete any lessons',
            'restore any lessons',

            // Task
            'view any tasks',
            'update any tasks',
            'delete any tasks',

            // UserCourse
            'view any user courses',

            // UserLesson
            'view any user lessons',
            'restore any user lessons',
            'delete any user lessons',
        ]);
    }
}