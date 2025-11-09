<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Modules\Core\Models\Course;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Models\Lesson;

class UserLessonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $currentUser, User $targetUser = null): bool
    {
        // Пользователь может просматривать свои уроки
        if (!$targetUser || $currentUser->id === $targetUser->id) {
            return true;
        }

        // Администраторы могут просматривать уроки любого пользователя
        return $currentUser->hasPermissionTo('view any user lessons');
    }

    public function create(User $currentUser, User $targetUser, Lesson $lesson): bool
    {
        // Нельзя создавать прогресс для неопубликованных уроков
        if (!$lesson->course->is_published) {
            return false;
        }

        // Пользователь может создавать прогресс только для себя
        if ($currentUser->id !== $targetUser->id) {
            return false;
        }

        // Пользователь должен быть записан на курс
        return $lesson->course->users()->where('user_id', $targetUser->id)->exists();
    }

    public function restore(User $currentUser, User $targetUser, Lesson $lesson): bool
    {
        // Владелец курса может восстанавливать прогресс
        if ($currentUser->id === $lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут восстанавливать любой прогресс
        return $currentUser->hasPermissionTo('restore any user lessons');
    }

    public function deleteSoft(User $currentUser, User $targetUser, Lesson $lesson): bool
    {
        // Пользователь может удалять свой прогресс
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Владелец курса может удалять прогресс
        if ($currentUser->id === $lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут удалять любой прогресс
        return $currentUser->hasPermissionTo('delete any user lessons');
    }

    public function deleteHard(User $currentUser, User $targetUser, Lesson $lesson): bool
    {
        // Только администраторы могут полностью удалять
        return $currentUser->hasPermissionTo('force delete user lessons');
    }

    public function complete(User $currentUser, Lesson $lesson): bool
    {
        // Нельзя завершать неопубликованные уроки
        if (!$lesson->course->is_published) {
            return false;
        }

        // Пользователь должен быть записан на курс
        return $lesson->course->users()->where('user_id', $currentUser->id)->exists();
    }
}