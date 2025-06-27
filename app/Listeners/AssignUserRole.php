<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Role;

class AssignUserRole
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Get the freshly registered user
        $user = $event->user;

        // Find the “user” role and attach it
        $role = Role::firstWhere('name', 'user');
        if ($role) {
            $user->roles()->attach($role->id);
        }
    }
}
