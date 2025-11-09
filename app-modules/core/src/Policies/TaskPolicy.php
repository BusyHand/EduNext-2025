<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Modules\Core\Models\Course;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Models\Task;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view any tasks') ||
            $user->hasPermissionTo('view tasks');
    }

    public function view(User $user, Task $task): bool
    {
        // Пользователь может просматривать задания своих курсов
        if ($user->id === $task->lesson->course->owner_id) {
            return true;
        }

        // Пользователи, записанные на курс, могут просматривать задания
        if ($task->lesson->course->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // Администраторы могут просматривать любые задания
        return $user->hasPermissionTo('view any tasks') ||
            ($user->hasPermissionTo('view tasks') && $task->lesson->course->is_published);
    }

    public function answerTask(User $user, Task $task): bool
    {
        // Нельзя отвечать на задания неопубликованных уроков
        if (!$task->lesson->course->is_published) {
            return false;
        }

        // Только пользователи, записанные на курс, могут отвечать на задания
        return $task->lesson->course->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create tasks');
    }

    public function update(User $user, Task $task): bool
    {
        // Владелец курса может редактировать задания
        if ($user->id === $task->lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут редактировать любые задания
        return $user->hasPermissionTo('update any tasks') ||
            $user->hasPermissionTo('update tasks');
    }

    public function delete(User $user, Task $task): bool
    {
        // Владелец курса может удалять задания
        if ($user->id === $task->lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут удалять любые задания
        return $user->hasPermissionTo('delete any tasks') ||
            $user->hasPermissionTo('delete tasks');
    }
}