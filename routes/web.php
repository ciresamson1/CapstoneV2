<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\Task;

Route::get('/dashboard', function () {

    $totalProjects = Project::count();
    $totalTasks = Task::count();

    $completedTasks = Task::where('status','completed')->count();
    $pendingTasks = Task::where('status','pending')->count();

    $overdueTasks = Task::where('due_date','<',now())
        ->where('status','!=','completed')
        ->count();

    $recentProjects = Project::with('tasks')->latest()->take(5)->get();

    $recentTasks = Task::with('project')->latest()->take(5)->get();

    $recentTasks = Task::with('project')->latest()->take(5)->get();

    return view('dashboard', compact(
        'totalProjects',
        'totalTasks',
        'completedTasks',
        'pendingTasks',
        'overdueTasks',
        'recentProjects',
        'recentTasks'
    ));

})->middleware(['auth','verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users Only)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profile routes from Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Roles CRUD routes
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::get('/gantt', [App\Http\Controllers\TaskController::class, 'gantt'])
    ->name('tasks.gantt');

});

require __DIR__.'/auth.php';