<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $submissions = FormSubmission::where('status', 'pending_audit')
                         ->with('user')
                         ->latest()
                         ->get();

        return view('audit.submissions.index', compact('submissions'));
    }

    public function show(FormSubmission $submission)
    {
        abort_if($submission->status !== 'pending_audit', 403);
        return view('audit.submissions.show', compact('submission'));
    }

    public function update(Request $request, FormSubmission $submission)
    {
        abort_if($submission->status !== 'pending_audit', 403);
    
        $data = $request->validate([
            'title_ok'       => 'required|boolean',
            'description_ok' => 'required|boolean',
            'audit_comment'  => 'nullable|string|max:1000',
        ]);
    
        $allOk = $data['title_ok'] && $data['description_ok'];
    
        $submission->status        = $allOk ? 'pending_finance' : 'returned_to_user';
        $submission->audited_by    = $request->user()->id;
        $submission->audited_at    = now();
        $submission->audit_comment = $data['audit_comment'] ?? null;
        $submission->save();
    
        return redirect()
            ->route('audit.submissions.index')
            ->with('success', $allOk
                ? 'Submission approved and sent to Finance.'
                : 'Submission returned to user for corrections.');
    }
    

}
