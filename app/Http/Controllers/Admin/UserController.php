<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show all users with their current roles.
     */
    public function index()
    {
        // eager-load roles to avoid N+1
        $users = User::with('roles')->get();
        // only these two roles are promotable
        $roles = Role::whereIn('name', ['admin','audit','finance'])->get();

        return view('admin.users.index', compact('users','roles'));
    }

    /**
     * Attach the given role to the user.
     */
    public function promote(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required','in:admin,audit,finance'],
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();

        // Remove all previous roles in this group, then attach only the chosen one:
        $user->roles()->sync([$role->id]);

        return back()->with('success', "User promoted to “{$role->name}.”");
    }

}
