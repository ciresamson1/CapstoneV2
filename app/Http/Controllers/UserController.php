<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form to create a user
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a new user
     */
  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role_id' => 'required'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id
    ]);

    return redirect()->route('users.index')
        ->with('success', 'User created successfully');
}

    /**
     * Show the form to edit a user
     */
    public function edit(User $user)
        {
            $roles = Role::all();

            return view('users.edit', compact('user', 'roles'));
        }
    /**
     * Update user
     */
    public function update(Request $request, User $user)
        {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email',
                'role_id' => 'required'
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
        }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}