<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function editTask($id, TaskRequest $request)
    { //task id
        if (!$this->checkPermission('edit-task', 'admin'))
            return abort(403);
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
        if (!$this->checkPermission('delete-task', 'admin'))
            return abort(403);
        $task = Task::findOrFail($id);
        // check role
        $task->delete();
        return response()->json([
            "data" => $task,

        ], 200);
    }

    public function restoreTask($id)
    { //task id
        if (!$this->checkPermission('restore-task', 'admin'))
            return abort(403);
        $task = Task::onlyTrashed()->findOrFail($id);
        // check role
        $task->restore();
        return response()->json([
            "data" => $task,

        ], 200);
    }
    public function createTask(TaskRequest $request)
    {
        if (!$this->checkPermission('create-task', 'admin'))
            return abort(403);
        $data = $request->all();

        $task = Task::create($data);
        return response()->json([
            "data" => $task,

        ], 201);
    }
    public function reAssignTask($taskId, $userId)
    {
        if (!$this->checkPermission('reassign-task', 'admin'))
            return abort(403);
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
        if (!$this->checkPermission('restore-task', 'admin'))
            return abort(403);
        $user = User::findOrFail($userid);
        $task = $user->tasks()->onlyTrashed()->restore();
        // check role

        return response()->json([
            "data" => $task,

        ], 201);
    }
    public function assignProject($userId, $projectId)
    {
        if (!$this->checkPermission('assign-project', 'admin'))
            return abort(403);
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
    private function checkPermission($permission, $guard)
    {
        return Auth::user()->hasPermissionTo($permission, $guard);
    }
    public function uploadMedia(Request $request, $task)
    {
        if (!$request->hasFile('image')) {
            return false;
        }
        $task->addMedia($request->file('image'))
            ->usingFileName($task->name . "" . now())
            ->toMediaCollection('tas-media');
            return true;
    }

}
