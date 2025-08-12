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
    
        // Validation rules for the flags + comment
        $flagKeys = [
            'document_ok',
            'vendor_name_ok','schema_group_ok','account_group_ok','beneficiary_tin_ok',
            'fax_ok','bank_name_ok','bank_account_no_ok','vendor_email_ok','region_ok',
            'purchasing_group_ok','term_of_payment_ok',
            'contact_title_ok','contact_gender_ok','contact_first_name_ok',
            'contact_last_name_ok','contact_address_ok','contact_telephone_primary_ok',
            'contact_telephone_secondary_ok',
        ];
    
        $rules = array_fill_keys($flagKeys, 'required|boolean');
        $rules['finance_comment'] = 'nullable|string|max:1000';
    
        $data = $request->validate($rules);
    
        // Normalize incoming flag values into strict booleans
        // (Laravel will accept '1'/'0' for boolean, but these are strings — convert to real booleans)
        $flags = [];
        foreach ($flagKeys as $k) {
            $flags[$k] = filter_var($data[$k], FILTER_VALIDATE_BOOLEAN);
        }
    
        // Determine overall approval: every flag must be true
        $allOk = collect($flags)->every(fn($v) => $v === true);
    
        // Prepare update payload mapping to finance_* DB columns
        $update = [
            'finance_document_ok'             => $flags['document_ok'],
    
            'finance_vendor_name_ok'          => $flags['vendor_name_ok'],
            'finance_schema_group_ok'         => $flags['schema_group_ok'],
            'finance_account_group_ok'        => $flags['account_group_ok'],
            'finance_beneficiary_tin_ok'      => $flags['beneficiary_tin_ok'],
            'finance_fax_ok'                  => $flags['fax_ok'],
            'finance_bank_name_ok'            => $flags['bank_name_ok'],
            'finance_bank_account_no_ok'      => $flags['bank_account_no_ok'],
            'finance_vendor_email_ok'         => $flags['vendor_email_ok'],
            'finance_region_ok'               => $flags['region_ok'],
            'finance_purchasing_group_ok'     => $flags['purchasing_group_ok'],
            'finance_term_of_payment_ok'      => $flags['term_of_payment_ok'],
    
            'finance_contact_title_ok'               => $flags['contact_title_ok'],
            'finance_contact_gender_ok'              => $flags['contact_gender_ok'],
            'finance_contact_first_name_ok'          => $flags['contact_first_name_ok'],
            'finance_contact_last_name_ok'           => $flags['contact_last_name_ok'],
            'finance_contact_address_ok'             => $flags['contact_address_ok'],
            'finance_contact_telephone_primary_ok'   => $flags['contact_telephone_primary_ok'],
            'finance_contact_telephone_secondary_ok' => $flags['contact_telephone_secondary_ok'],
    
            'finance_comment' => $data['finance_comment'] ?? null,
        ];
    
        // Set status and metadata properly depending on the overall result
        if ($allOk) {
            $update['status']     = 'approved';
            $update['finance_by'] = $request->user()->id;
            $update['finance_at'] = now();
            $message = 'Submission approved—workflow complete (sent to I.T. for SAP processing).';
        } else {
            $update['status']     = 'returned_to_user';
            $update['finance_by'] = $request->user()->id;
            $update['finance_at'] = now();
            $message = 'Submission returned to user for corrections.';
        }
    
        // Persist everything
        $submission->update($update);
    
        return redirect()
            ->route('finance.submissions.index')
            ->with('success', $message);
    }
    
    
}
