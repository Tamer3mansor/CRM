<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\User;
use \App\Models\Task;
use Auth;
use Laravel\Telescope\AuthorizesRequests;
class UserController extends Controller
{
    use AuthorizesRequests;
    public function index($id)
    {
        if (!$this->checkPermission('view-task', 'web'))
            return abort(403);
        $user = User::with('tasks:id,name,status,deadline')->findOrFail($id);
        // $tasks = $user->tasks;#()->select('id', 'name')->get();
        return response()->json([
            "data" => $user->tasks,

        ], 200);
    }
    public function view_task($id)
    { // id for task
        $task = Task::findOrFail($id);
        $task_in_id = $task->user->id;
        if (!$this->checkPermission('view-task', 'web') or !$this->checkAbility($task_in_id))
            return abort(403);
        else
            return response()->json([
                "data" => $task,

            ], 200);
    }
    public function changeProgress($id, $progress)
    { //task id

        $task = Task::findOrFail($id);
        $task_in_id = $task->user->id;
        $this->authorize('viewTask', $task);
        // return abort(code: 403);
        // check role
        $task->update(
            ["progress" => $progress]
        );
        return response()->json([
            "data" => $task,

        ], 200);
    }


    private function checkAbility($id)
    {
        return Auth::user()->id === $id;
    }
    private function checkPermission($permission, $guard)
    {
        return Auth::user()->hasPermissionTo([$permission, $guard]);
    }

}
