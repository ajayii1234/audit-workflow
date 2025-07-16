<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $submissions = FormSubmission::where('status', 'pending_finance')
                         ->with('user')
                         ->latest()
                         ->get();

        return view('finance.submissions.index', compact('submissions'));
    }

    public function show(FormSubmission $submission)
    {
        abort_if($submission->status !== 'pending_finance', 403);
        return view('finance.submissions.show', compact('submission'));
    }

    public function update(Request $request, FormSubmission $submission)
    {
        abort_if($submission->status !== 'pending_finance', 403);
    
        $data = $request->validate([
            'title_ok'        => 'required|boolean',
            'description_ok'  => 'required|boolean',
            'finance_comment' => 'nullable|string|max:1000',
        ]);
    
        $allOk = $data['title_ok'] && $data['description_ok'];
    
        $submission->status          = $allOk ? 'approved' : 'returned_to_user';
        $submission->finance_by      = $allOk ? null : $request->user()->id;
        $submission->finance_at      = $allOk ? null : now();
        $submission->finance_comment = $data['finance_comment'] ?? null;
        $submission->save();
    
        return redirect()
            ->route('finance.submissions.index')
            ->with('success', $allOk
                ? 'Submission approved—workflow complete.'
                : 'Submission returned to user for corrections.');
    }
    
    
}
