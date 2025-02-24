<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('{guard}')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('users')->middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('{id}', [UserController::class, 'index']); // جلب مهام المستخدم
    Route::patch('change-task-progress/{id}', [UserController::class, 'changeProgress']); // تعديل مهمة
});
route::prefix('admin')->middleware(['auth:admin', 'role:admin'])->group(function () {
    Route::patch('edit-task/{id}', [AdminController::class, 'editTask']); // تعديل مهمة
    Route::post('assign-project/{userid}/{projectid}', [AdminController::class, 'assignProjects']); // إسناد مشروع إلى مستخدم
    Route::delete('delete-task/{id}', [AdminController::class, 'deleteTask']); // حذف مهمة
    Route::post('create-task', [AdminController::class, 'createTask']); // إنشاء مهمة جديدة
    Route::patch('reassign-task/{taskId}/{userId}', [AdminController::class, 'reAssignTask']); // إعادة تعيين المهمة
    Route::patch('restore-task/{id}', [AdminController::class, 'restoreTask']); // استعادة مهمة محذوفة
    Route::patch('restore-user-tasks/{userid}', [AdminController::class, 'restore']); // استعادة كل المهام المحذوفة لمستخدم معين
});
// ✅ مجموعة مسارات خاصة بالمهام
Route::prefix('tasks')->middleware(['auth:admin', 'role:admin'])->group(function () {
    Route::get('/', [TaskController::class, 'Tasks']); // جلب جميع المهام مع المستخدمين
    Route::delete('delete-all', [TaskController::class, 'deleteAllTasks']); // حذف كل المهام
    Route::patch('restore-all', [TaskController::class, 'restoreAll']); // استعادة جميع المهام المحذوفة
});
