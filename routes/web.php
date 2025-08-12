<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ITSubmissionController;
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

         // NEW: toggle search access *under* users/
         Route::post('users/{user}/toggle-search', [UserController::class, 'toggleSearch'])
              ->name('users.toggle_search');

         // (optional) Admin dashboard
         Route::get('/', [UserController::class, 'index'])->name('index');
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
         // … your create/store/index routes …

         // 1) My Submissions index
         Route::get('user/submissions', 
             [FormSubmissionController::class, 'index'])
             ->name('user.submissions.index');

         // 2) NEW: Search Submissions page
         Route::get('user/submissions/search',
             [FormSubmissionController::class, 'search'])
             ->name('user.submissions.search');

         // 3) Returned
         Route::get('user/submissions/returned', 
             [FormSubmissionController::class, 'returned'])
             ->name('user.submissions.returned');

         // 4) Show a submission
         Route::get('user/submissions/{submission}', 
             [FormSubmissionController::class, 'show'])
             ->whereNumber('submission')       // ensure numeric
             ->name('user.submissions.show');

         // 5) Edit & update
         Route::get('user/submissions/{submission}/edit', 
             [FormSubmissionController::class, 'edit'])
             ->name('user.submissions.edit');
         Route::put('user/submissions/{submission}', 
             [FormSubmissionController::class, 'update'])
             ->name('user.submissions.update');
});


Route::middleware(['auth','role:it'])
     ->prefix('it')
     ->name('it.')
     ->group(function () {
         // static routes first (no parameters)
         Route::get('submissions/submitted', [ITSubmissionController::class, 'submitted'])
              ->name('submissions.submitted');

         // list of pending-for-IT submissions
         Route::get('submissions', [ITSubmissionController::class, 'index'])
              ->name('submissions.index');

         // parameter routes last
         Route::get('submissions/{submission}', [ITSubmissionController::class, 'show'])
              ->name('submissions.show');

         Route::put('submissions/{submission}', [ITSubmissionController::class, 'update'])
              ->name('submissions.update');
});

require __DIR__.'/auth.php';
