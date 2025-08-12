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
        $roles = Role::whereIn('name', ['user','admin','audit','finance','it'])->get();

        return view('admin.users.index', compact('users','roles'));
    }


            public function toggleSearch(User $user)
        {
            // flip the flag
            $user->can_search = ! $user->can_search;
            $user->save();

            return back()->with('success', 
                $user->can_search
                ? "Granted search access to {$user->email}."
                : "Revoked search access from {$user->email}."
            );
        }

    /**
     * Sync the given user to exactly the chosen role.
     */
    public function promote(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required','in:user,admin,audit,finance,it'],
        ]);

        $role = Role::firstWhere('name', $request->role);

        // Remove any other roles and attach only this one
        $user->roles()->sync([$role->id]);

        return back()->with('success', "User role changed to “{$role->name}.”");
    }
}
