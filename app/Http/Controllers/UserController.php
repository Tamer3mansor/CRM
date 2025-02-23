<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\User;
use \App\Models\Task;
class UserController extends Controller
{
    public function index($id)
    {
        $user = User::with('tasks:id,name,status,deadline')->findOrFail($id);
        // $tasks = $user->tasks;#()->select('id', 'name')->get();
        return response()->json([
            "data" => $user->tasks,

        ], 200);
    }

    public function editTask($id, TaskRequest $request)
    { //task id
        $data = $request->all();
        $task = Task::findOrFail($id);
        // check role
        $task->update(
            $data
        );
        return response()->json([
            "data" => $task,

        ], 200);
    }
    public function deleteTask($id)
    { //task id

        $task = Task::findOrFail($id);
        // check role
        $task->delete();
        return response()->json([
            "data" => $task,

        ], 200);
    }

    public function restoreTask($id)
    { //task id

        $task = Task::onlyTrashed()->findOrFail($id);
        // check role
        $task->restore();
        return response()->json([
            "data" => $task,

        ], 200);
    }
    public function createTask(TaskRequest $request)
    {
        $data = $request->all();

        $task = Task::create($data);
        return response()->json([
            "data" => $task,

        ], 201);
    }
    public function reAssignTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $user = User::findOrFail($userId);
        if ($user) {
            $task->update([
                'user_id' => $userId
            ]);
        }
        return response()->json([
            "data" => $task,

        ], 200);

    }
    public function restore($userid)
    { //user id

        $user = User::findOrFail($userid);
        $task = $user->tasks()->onlyTrashed()->restore();
        // check role

        return response()->json([
            "data" => $task,

        ], 201);
    }
    public function assignProject($userId, $projectId)
    {
        // Find the user and project
        $user = User::findOrFail($userId);
        // $project = Project::findOrFail($projectId);

        // Check if the user is already assigned to the project
        if ($user->projects()->where('project_id', $projectId)->exists()) {
            return response()->json(["message" => "User is already assigned to this project"], 409);
        }

        // Assign project to user
        $user->projects()->attach($projectId);

        return response()->json(["message" => "Project assigned successfully"], 201);
    }




}
