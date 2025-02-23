<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function restoreAll()
    { //task id

        $task = Task::onlyTrashed()->restore();
        // check role
        return response()->json([
            "data" => $task,

        ], 200);
    }
    public function deleteAllTasks()
    { //task id


        // check role
        Task::query()->delete();
        return response()->json([
            "msg" => "success deletion"
        ], 200);
    }
    public function Tasks()
    {
        $user = User::with('tasks:id,name,status,deadline')->get();
        return response()->json([
            "data" => $user,

        ], 200);
    }
}
