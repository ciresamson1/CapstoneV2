<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display task list
     */
    public function index()
    {
        $tasks = Task::with(['project','assignee'])
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $projects = Project::all();
        $users = User::all();

        return view('tasks.create', compact('projects','users'));
    }

    /**
     * Store task
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'project_id' => 'required',
            'assigned_to' => 'required',
            'start_date' => 'required|date',
            'due_date' => 'required|date'
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'status' => 'pending',
            'start_date' => $request->start_date,
            'due_date' => $request->due_date
        ]);

        return redirect()->route('tasks.index')
            ->with('success','Task created successfully');
    }

    /**
     * Show edit page
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();

        return view('tasks.edit', compact('task','projects','users'));
    }

    /**
     * Update task
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'project_id' => 'required',
            'assigned_to' => 'required',
            'start_date' => 'required',
            'due_date' => 'required',
            'status' => 'required'
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')
            ->with('success','Task updated successfully');
    }

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success','Task deleted successfully');
    }

        /**
     * Display Gantt chart
     */
   public function gantt()
{
    $tasks = Task::all();

    $ganttTasks = $tasks->map(function ($task) {
        return [
            'id' => (string) $task->id,
            'name' => $task->title,
            'start' => \Carbon\Carbon::parse($task->start_date)->format('Y-m-d'),
            'end' => \Carbon\Carbon::parse($task->due_date)->format('Y-m-d'),
            'progress' => $task->status === 'completed' ? 100 : 0
        ];
    });

    return view('tasks.gantt', [
        'tasks' => $ganttTasks->values()
    ]);
}


}

   /**
 * Display Gantt chart per project
 */
public function gantt(Project $project)
{
    $tasks = $project->tasks;

    $ganttTasks = $tasks->map(function ($task) {
        return [
            'id' => (string) $task->id,
            'name' => $task->title,
            'start' => \Carbon\Carbon::parse($task->start_date)->format('Y-m-d'),
            'end' => \Carbon\Carbon::parse($task->due_date)->format('Y-m-d'),
            'progress' => $task->status === 'completed' ? 100 : 0
        ];
    });

    return view('tasks.gantt', [
        'project' => $project,
        'tasks' => $ganttTasks
    ]);
}