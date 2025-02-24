<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function viewTask(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->hasPermissionTo('view-task');
    }

    public function deleteTask(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->hasRole('admin');
    }
}
