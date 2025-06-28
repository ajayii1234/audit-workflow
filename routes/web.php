<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\FinanceController;
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

// Only authenticated “user” role may submit forms
Route::middleware(['auth','role:user'])
     ->group(function () {
         // Show the submission form
         Route::get('/user/form', [FormSubmissionController::class, 'create'])
              ->name('user.form.create');

         // Handle the form POST
         Route::post('/user/form', [FormSubmissionController::class, 'store'])
              ->name('user.form.store');
     });


     Route::middleware(['auth','role:audit'])
     ->prefix('audit')
     ->name('audit.')
     ->group(function () {
         // List all pending-audit submissions
         Route::get('submissions', [AuditController::class, 'index'])
              ->name('submissions.index');

         // Show a single submission for review
         Route::get('submissions/{submission}', [AuditController::class, 'show'])
              ->name('submissions.show');

         // Process the review
         Route::post('submissions/{submission}', [AuditController::class, 'update'])
              ->name('submissions.update');
});


Route::middleware(['auth','role:finance'])
     ->prefix('finance')
     ->name('finance.')
     ->group(function () {
         // List all pending‑finance submissions
         Route::get('submissions',   [FinanceController::class, 'index'])
              ->name('submissions.index');
         // Show one for review
         Route::get('submissions/{submission}', [FinanceController::class, 'show'])
              ->name('submissions.show');
         // Process the review
         Route::post('submissions/{submission}', [FinanceController::class, 'update'])
              ->name('submissions.update');
});

Route::middleware(['auth','role:user'])
     ->group(function () {
         // …existing create/store routes…

         // List returned submissions
         Route::get('user/submissions/returned', 
             [FormSubmissionController::class, 'returned'])
             ->name('user.submissions.returned');

         // Edit a returned submission
         Route::get('user/submissions/{submission}/edit', 
             [FormSubmissionController::class, 'edit'])
             ->name('user.submissions.edit');

         // Update the submission
         Route::put('user/submissions/{submission}', 
             [FormSubmissionController::class, 'update'])
             ->name('user.submissions.update');
});

require __DIR__.'/auth.php';
