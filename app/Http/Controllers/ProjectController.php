<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    /**
     * Display project list with search + filters
     */
    public function index(Request $request)
    {
        $query = Project::with('manager');

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // FILTER BY MANAGER
        if ($request->manager) {
            $query->where('manager_id', $request->manager);
        }

        // FILTER BY STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(10);

        // dropdown data
        $managers = User::all();

        return view('projects.index', compact('projects', 'managers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $users = User::all();

        return view('projects.create', compact('users'));
    }

    /**
     * Store project
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'manager_id' => 'required'
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'planning',
            'manager_id' => $request->manager_id,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Project $project)
    {
        $users = User::all();

        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update project
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'manager_id' => 'required'
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'manager_id' => $request->manager_id
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully');
    }

    /**
     * Delete project
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully');
    }


    public function show(Project $project)
{
    $tasks = $project->tasks()->with('assignee')->get();

    // Timeline
    $timelineStart = $tasks->min('start_date');
    $timelineEnd = $tasks->max('due_date');

    $timelineStart = $timelineStart ? \Carbon\Carbon::parse($timelineStart)->startOfDay() : now();
    $timelineEnd = $timelineEnd ? \Carbon\Carbon::parse($timelineEnd)->endOfDay() : now()->addDays(7);

    // Prepare Gantt data
    $ganttTasks = $tasks->map(function ($task) {

        $start = \Carbon\Carbon::parse($task->start_date);
        $end = \Carbon\Carbon::parse($task->due_date);

        return [
            'name' => $task->title,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'progress' => $task->status === 'completed' ? 100 : 50,
            'assigned' => $task->assignee->name ?? 'N/A',
            'status' => $task->status,
            'due' => $task->due_date
        ];
    });

    return view('projects.show', compact(
        'project',
        'tasks',
        'ganttTasks',
        'timelineStart',
        'timelineEnd'
    ));
}


}