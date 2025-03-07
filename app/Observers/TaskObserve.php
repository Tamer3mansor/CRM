<?php

namespace App\Observers;

use App\Events\CreateTask;
use App\Models\Task;
use App\Notifications\TaskCreateNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class TaskObserve
{
    /**composer require spatie/laravel-medialibrary

     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        Event(new CreateTask($task));
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
