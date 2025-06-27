<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users and their roles.
     */
    public function index()
    {
        // eager-load roles to avoid N+1
        $users = User::with('roles')->get();

        // all possible roles
        $roles = Role::whereIn('name', ['user','admin','audit','finance'])->get();

        return view('admin.users.index', compact('users','roles'));
    }

    /**
     * Sync the given user to exactly the chosen role.
     */
    public function promote(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required','in:user,admin,audit,finance'],
        ]);

        $role = Role::firstWhere('name', $request->role);

        // Remove any other roles and attach only this one
        $user->roles()->sync([$role->id]);

        return back()->with('success', "User role changed to “{$role->name}.”");
    }
}
