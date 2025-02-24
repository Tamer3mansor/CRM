<?php

namespace App\Listeners;

use App\Events\CreateTask;
use App\Notifications\TaskCreateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTaskListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreateTask $event): void
    {
        $task = $event->task;
        $task->user->notify(new TaskCreateNotification($task));

    }
}
