<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;

class ITSubmissionController extends Controller
{
    public function __construct()
    {
        // controller extends base Controller so middleware() works
        $this->middleware(['auth', 'role:it']);
    }

    /**
     * Show submissions for I.T. to process.
     * I.T. should see submissions that finance has approved but not yet submitted to SAP.
     */
    public function index()
    {
        $submissions = FormSubmission::where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('it_submitted')->orWhere('it_submitted', false);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('it.submissions.index', compact('submissions'));
    }

    /**
     * Show single submission for IT to inspect.
     */
    public function show(FormSubmission $submission)
    {
        // Only allow viewing approved items
        abort_if($submission->status !== 'approved', 403);

        return view('it.submissions.show', compact('submission'));
    }

    /**
     * Update — mark submission as submitted to SAP (or note not-submitted).
     */
    public function update(Request $request, FormSubmission $submission)
    {
        // Only allow marking approved items
        abort_if($submission->status !== 'approved', 403);

        $data = $request->validate([
            'it_submitted' => 'required|boolean',
            'it_comment'   => 'nullable|string|max:1000',
        ]);

        if ((bool) $data['it_submitted']) {
            $submission->update([
                'it_submitted'     => true,
                'it_comment'       => $data['it_comment'] ?? null,
                'it_submitted_by'  => $request->user()->id,
                'it_submitted_at'  => now(),
                'status'           => 'sap_submitted', // final state after SAP push
            ]);

            return redirect()
                ->route('it.submissions.index')
                ->with('success', 'Marked submission as submitted to SAP.');
        }

        // IT marked as not submitted (note only) — keep status = approved
        $submission->update([
            'it_submitted'     => false,
            'it_comment'       => $data['it_comment'] ?? null,
            'it_submitted_by'  => $request->user()->id,
            'it_submitted_at'  => now(),
        ]);

        return redirect()
            ->route('it.submissions.index')
            ->with('success', 'I.T. note saved; submission remains approved.');
    }

        // NEW: list all submissions that have been marked as submitted to SAP (final)
        public function submitted()
        {
            $submissions = FormSubmission::where('status', 'sap_submitted')
                          ->orderBy('it_at', 'desc')
                          ->get();
    
            return view('it.submissions.submitted', compact('submissions'));
        }
}
