<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display projects list
     */
    public function index()
    {
        $projects = Project::with('manager')
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show create project form
     */
    public function create()
    {
        // get users to assign as manager
        $users = User::all();

        return view('projects.create', compact('users'));
    }

    /**
     * Store new project
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
}