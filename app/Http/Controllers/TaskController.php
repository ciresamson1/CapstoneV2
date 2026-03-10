<?php

namespace App\Http\Controllers;

use App\Models\Task;

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
}