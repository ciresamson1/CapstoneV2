<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;



Route::get('/dashboard', function () {

    $totalProjects = Project::count();
    $totalTasks = Task::count();

    $completedTasks = Task::where('status','completed')->count();
    $pendingTasks = Task::where('status','pending')->count();

   

    $totalUsers = User::count();

    $dueSoonTasks = Task::whereBetween('due_date',[now(),now()->addDays(3)])
        ->where('status','!=','completed')
        ->take(5)
        ->get();

    $recentProjects = Project::with('tasks')->latest()->take(5)->get();

    $recentTasks = Task::with('project')->latest()->take(5)->get();

    // ACTIVE PROJECTS
$activeProjects = Project::count();

// ROLE-BASED TASKS
$user = auth()->user();

if ($user->role && $user->role->name === 'Project Manager') {
    $myTasks = Task::count();

    // MY TASKS (latest 5 assigned to logged user)
$myRecentTasks = Task::where('assigned_to', auth()->id())
    ->with('project')
    ->latest()
    ->take(5)
    ->get();


} else {
    $myTasks = Task::where('assigned_to', $user->id)->count();
}

// DUE SOON (next 5 days)
$dueSoonCount = Task::whereBetween('due_date', [now(), now()->addDays(5)])
    ->where('status', '!=', 'completed')
    ->count();

// OVERDUE
$overdueTasks = Task::where('due_date', '<', now())
    ->where('status', '!=', 'completed')
    ->count();

$myRecentTasks = Task::where('assigned_to', auth()->id())
    ->with('project')
    ->latest()
    ->take(5)
    ->get();


// GET LATEST PROJECT WITH TASKS
$latestProject = Project::with('tasks')->latest()->first();


$ganttTasks = [];

if ($latestProject) {

$timelineStart = null;
$timelineEnd = null;

if ($latestProject && $latestProject->tasks->count()) {

    $timelineStart = $latestProject->tasks->min('created_at');
    $timelineEnd = $latestProject->tasks->max('due_date');

    $timelineStart = Carbon::parse($timelineStart)->startOfDay();
    $timelineEnd = Carbon::parse($timelineEnd)->endOfDay();
}

    foreach ($latestProject->tasks as $task) {

        $start = \Carbon\Carbon::parse($task->created_at);
        $end = \Carbon\Carbon::parse($task->due_date);

        $today = now();

        // STATUS COLOR
        if ($task->status === 'completed') {
            $color = 'green';
        } elseif ($end->isPast()) {
            $color = 'red';
        } elseif ($end->diffInDays($today) <= 3) {
            $color = 'yellow';
        } else {
            $color = 'blue';
        }

        $ganttTasks[] = [
            'name' => $task->title,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'color' => $color,
        ];
    }
}




    // TEAM WORKLOAD
    $users = User::withCount('tasks')->get();

    $userNames = $users->pluck('name');
    $userTaskCounts = $users->pluck('tasks_count');

    // ACTIVITY LOG
    $activities = Task::with(['assignee.role'])
        ->latest()
        ->take(12)
        ->get()
        ->map(function($task){

        $user = $task->assignee->name ?? 'Unknown';
        $role = $task->assignee->role->name ?? '';

        return [
        'message' => $user . ' (' . $role . ') updated task "' . $task->title . '"',
        'time' => $task->updated_at
        ];

    });

    return view('dashboard', compact(
        'totalProjects',
        'totalTasks',
        'completedTasks',
        'pendingTasks',
        'overdueTasks',
        'totalUsers',
        'dueSoonTasks',
        'recentProjects',
        'recentTasks',
        'activities',
        'userNames',
        'userTaskCounts',
        'activeProjects',
        'myTasks',
        'dueSoonCount',
        'overdueTasks',
        'latestProject',
        'ganttTasks',
        'timelineStart',
        'timelineEnd',
        'myRecentTasks'
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
    Route::get('/gantt/{project}', [TaskController::class, 'gantt'])
    ->name('tasks.gantt');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])
    ->name('projects.show');

});

require __DIR__.'/auth.php';