<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Modules\Core\Models\Course;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Models\Lesson;

class LessonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view any lessons') ||
            $user->hasPermissionTo('view lessons');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lesson $lesson): bool
    {
        // Пользователь может просматривать уроки своих курсов
        if ($user->id === $lesson->course->owner_id) {
            return true;
        }

        // Пользователи, записанные на курс, могут просматривать уроки
        if ($lesson->course->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // Администраторы могут просматривать любые уроки
        return $user->hasPermissionTo('view any lessons') ||
            ($user->hasPermissionTo('view lessons') && $lesson->course->is_published);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Course $course): bool
    {
        // Владелец курса может создавать уроки
        if ($user->id === $course->owner_id) {
            return true;
        }

        // Администраторы могут создавать уроки в любых курсах
        return $user->hasPermissionTo('create any lessons') ||
            $user->hasPermissionTo('create lessons');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        // Владелец курса может редактировать уроки
        if ($user->id === $lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут редактировать любые уроки
        return $user->hasPermissionTo('update any lessons') ||
            $user->hasPermissionTo('update lessons');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        // Владелец курса может удалять уроки
        if ($user->id === $lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут удалять любые уроки
        return $user->hasPermissionTo('delete any lessons') ||
            $user->hasPermissionTo('delete lessons');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lesson $lesson): bool
    {
        // Владелец курса может восстанавливать уроки
        if ($user->id === $lesson->course->owner_id) {
            return true;
        }

        // Администраторы могут восстанавливать любые уроки
        return $user->hasPermissionTo('restore any lessons') ||
            $user->hasPermissionTo('restore lessons');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        // Только администраторы могут полностью удалять
        return $user->hasPermissionTo('force delete lessons');
    }

    /**
     * Determine whether the user can ask questions in the lesson.
     */
    public function askQuestion(User $user, Lesson $lesson): bool
    {
        // Нельзя задавать вопросы в неопубликованных уроках
        if (!$lesson->course->is_published) {
            return false;
        }

        // Только пользователи, записанные на курс, могут задавать вопросы
        return $lesson->course->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can generate tasks for the lesson.
     */
    public function generateTask(User $user, Lesson $lesson): bool
    {
        // Нельзя генерировать задания для неопубликованных уроков
        if (!$lesson->course->is_published) {
            return false;
        }

        // Только пользователи, записанные на курс, могут генерировать задания
        return $lesson->course->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can answer tasks.
     */
    public function answerTask(User $user, Lesson $lesson): bool
    {
        // Нельзя отвечать на задания в неопубликованных уроках
        if (!$lesson->course->is_published) {
            return false;
        }

        // Только пользователи, записанные на курс, могут отвечать на задания
        return $lesson->course->users()->where('user_id', $user->id)->exists();
    }
}