<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Modules\Core\Models\Course;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create courses');
    }

    public function update(User $user, Course $course): bool
    {
        // Владелец курса может редактировать
        if ($user->id === $course->owner_id) {
            return true;
        }

        // Администраторы могут редактировать любые курсы
        return $user->hasPermissionTo('update any courses') ||
            $user->hasPermissionTo('update courses');
    }

    public function delete(User $user, Course $course): bool
    {
        // Владелец курса может удалять
        if ($user->id === $course->owner_id) {
            return true;
        }

        // Администраторы могут удалять любые курсы
        return $user->hasPermissionTo('delete any courses') ||
            $user->hasPermissionTo('delete courses');
    }

    public function restore(User $user): bool
    {
        return $user->hasPermissionTo('restore any courses') ||
            $user->hasPermissionTo('restore courses');
    }

    public function forceDelete(User $user): bool
    {
        // Только администраторы могут полностью удалять
        return $user->hasPermissionTo('force delete courses');
    }

    public function enroll(User $user, Course $course): bool
    {
        // Нельзя записываться на неопубликованные курсы
        if (!$course->is_published) {
            return false;
        }

        // Уже записанные пользователи не могут записаться снова
        if ($course->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Пользователи с правом записи на курсы
        return $user->hasPermissionTo('enroll in courses');
    }

    public function manageUsers(User $user, Course $course): bool
    {
        // Владелец может управлять пользователями
        if ($user->id === $course->owner_id) {
            return true;
        }

        // Администраторы могут управлять пользователями любых курсов
        return $user->hasPermissionTo('manage course users');
    }
}