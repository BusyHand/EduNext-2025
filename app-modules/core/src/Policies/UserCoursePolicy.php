<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Modules\Core\Models\Course;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserCoursePolicy
{
    use HandlesAuthorization;

    public function enroll(User $currentUser, User $targetUser, Course $course): bool
    {
        if (!$course->is_published) {
            return false;
        }

        if ($course->users()->where('user_id', $targetUser->id)->exists()) {
            return false;
        }

        if ($currentUser->id === $targetUser->id) {
            return $currentUser->hasPermissionTo('enroll in courses');
        }

        return $this->manageUsers($currentUser, $course);
    }

    public function restore(User $currentUser, User $targetUser, Course $course): bool
    {
        return $this->manageUsers($currentUser, $course);
    }

    public function deleteSoft(User $currentUser, User $targetUser, Course $course): bool
    {
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        return $this->manageUsers($currentUser, $course);
    }

    public function deleteHard(User $currentUser, User $targetUser, Course $course): bool
    {
        return $this->manageUsers($currentUser, $course);
    }

    public function viewAny(User $currentUser, User $targetUser = null): bool
    {
        if (!$targetUser || $currentUser->id === $targetUser->id) {
            return true;
        }

        return $currentUser->hasPermissionTo('view any user courses');
    }

    private function manageUsers(User $user, Course $course): bool
    {
        if ($user->id === $course->owner_id) {
            return true;
        }

        return $user->hasPermissionTo('manage course users');
    }
}