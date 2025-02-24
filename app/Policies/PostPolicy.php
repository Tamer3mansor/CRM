<?php

namespace App\Policies;

use App\Models\Task;
use Illuminate\Auth\Access\Response;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->can('view-tasks');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Task $task): bool
    {
        return $user->can('view-task') && ($user->id == $task->user()->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->can('create-task');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user): bool
    {
        return $user->can('edit-task');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('delete-task');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user): bool
    {
        return $user->can('restore-task');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function changeProgress($user, Task $task): bool
    {
        return $user->can('change-progress') && ($user->id === $task->user()->id);
    }
}
