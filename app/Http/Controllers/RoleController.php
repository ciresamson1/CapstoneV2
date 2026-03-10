<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display all roles
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show form to create a new role
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
            'description' => 'nullable|max:255'
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|max:255'
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Delete role
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}