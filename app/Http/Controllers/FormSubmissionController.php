<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    /**
     * Show the user form.
     */
    public function create()
    {
        return view('user.form');
    }

    /**
     * Store a new submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['required','string'],
            // add other fields/validation rules here as needed
        ]);

        // Create with default status = pending_audit
        $request->user()->submissions()->create($data);

        return redirect()
            ->route('user.form.create')
            ->with('success','Form submitted! It’s now pending audit.');
    }

    public function returned()
    {
        $submissions = auth()
            ->user()
            ->submissions()
            ->where('status', 'returned_to_user')
            ->latest()
            ->get();

        return view('user.submissions.returned', compact('submissions'));
    }

    /**
     * Show the edit form for a returned submission.
     */
    public function edit(FormSubmission $submission)
    {
        // Ensure the user owns it and it was returned
        abort_if(
            $submission->user_id !== auth()->id() ||
            $submission->status !== 'returned_to_user',
            403
        );

        return view('user.submissions.edit', compact('submission'));
    }

    /**
     * Process the correction and resubmit for audit.
     */
    public function update(Request $request, FormSubmission $submission)
    {
        abort_if(
            $submission->user_id !== auth()->id() ||
            $submission->status !== 'returned_to_user',
            403
        );

        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['required','string'],
            // validate other fields as necessary
        ]);

        $submission->update($data + [
            'status' => 'pending_audit'
        ]);

        return redirect()
            ->route('user.submissions.returned')
            ->with('success', 'Submission updated and resubmitted for audit.');
    }
}
