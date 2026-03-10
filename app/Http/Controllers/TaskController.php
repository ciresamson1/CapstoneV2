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
     * Show create task form
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
}