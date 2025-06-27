<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;


// test data
Route::get('/test-audit', function () {
    return '✅ You’re in the Audit area!';
})->middleware(['auth', 'role:audit']);

Route::get('/test-finance', function () {
    return '✅ You’re in the Finance area!';
})->middleware(['auth', 'role:finance']);

Route::get('/test-finance', function () {
    return '✅ You’re in the Admin area!';
})->middleware(['auth', 'role:admin']);




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/audit', function () {
    return 'Audit Dashboard';
})->middleware(['auth', 'role:audit']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         // List all users
         Route::get('users', [UserController::class, 'index'])
              ->name('users.index');

         // Promote a user to audit or finance
         Route::post('users/{user}/promote', [UserController::class, 'promote'])
              ->name('users.promote');
});

require __DIR__.'/auth.php';
