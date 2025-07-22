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
    
        // 1) Validate every *_ok flag plus optional comment
        $data = $request->validate(array_merge([
            'document_ok'     => 'required|boolean',
            'finance_comment' => 'nullable|string|max:1000',
        ], array_fill_keys([
            'vendor_name_ok','schema_group_ok','account_group_ok','beneficiary_tin_ok',
            'fax_ok','bank_name_ok','bank_account_no_ok','vendor_email_ok','region_ok',
            'purchasing_group_ok','term_of_payment_ok',
            'contact_title_ok','contact_gender_ok','contact_first_name_ok',
            'contact_last_name_ok','contact_address_ok','contact_telephone_primary_ok',
            'contact_telephone_secondary_ok',
        ], 'required|boolean')));
    
        // 2) Collect all flags and check truthiness
        $allFlags = [
            $data['document_ok'],
            $data['vendor_name_ok'],
            $data['schema_group_ok'],
            $data['account_group_ok'],
            $data['beneficiary_tin_ok'],
            $data['fax_ok'],
            $data['bank_name_ok'],
            $data['bank_account_no_ok'],
            $data['vendor_email_ok'],
            $data['region_ok'],
            $data['purchasing_group_ok'],
            $data['term_of_payment_ok'],
            $data['contact_title_ok'],
            $data['contact_gender_ok'],
            $data['contact_first_name_ok'],
            $data['contact_last_name_ok'],
            $data['contact_address_ok'],
            $data['contact_telephone_primary_ok'],
            $data['contact_telephone_secondary_ok'],
        ];
    
        // Use loose truthiness so "1" casts to true
        $allOk = collect($allFlags)->every(fn($v) => (bool) $v);
    
        // 3) Prepare update payload
        $update = [
            'finance_document_ok'             => $data['document_ok'],
            'finance_vendor_name_ok'          => $data['vendor_name_ok'],
            'finance_schema_group_ok'         => $data['schema_group_ok'],
            'finance_account_group_ok'        => $data['account_group_ok'],
            'finance_beneficiary_tin_ok'      => $data['beneficiary_tin_ok'],
            'finance_fax_ok'                  => $data['fax_ok'],
            'finance_bank_name_ok'            => $data['bank_name_ok'],
            'finance_bank_account_no_ok'      => $data['bank_account_no_ok'],
            'finance_vendor_email_ok'         => $data['vendor_email_ok'],
            'finance_region_ok'               => $data['region_ok'],
            'finance_purchasing_group_ok'     => $data['purchasing_group_ok'],
            'finance_term_of_payment_ok'      => $data['term_of_payment_ok'],
            'finance_contact_title_ok'               => $data['contact_title_ok'],
            'finance_contact_gender_ok'              => $data['contact_gender_ok'],
            'finance_contact_first_name_ok'          => $data['contact_first_name_ok'],
            'finance_contact_last_name_ok'           => $data['contact_last_name_ok'],
            'finance_contact_address_ok'             => $data['contact_address_ok'],
            'finance_contact_telephone_primary_ok'   => $data['contact_telephone_primary_ok'],
            'finance_contact_telephone_secondary_ok' => $data['contact_telephone_secondary_ok'],
            'finance_comment'                     => $data['finance_comment'] ?? null,
            'status'                              => $allOk ? 'approved' : 'returned_to_user',
            'finance_by'                          => $allOk ? null : $request->user()->id,
            'finance_at'                          => $allOk ? null : now(),
        ];
    
        // 4) Persist
        $submission->update($update);
    
        // 5) Redirect
        return redirect()
            ->route('finance.submissions.index')
            ->with('success',
                $allOk
                    ? 'Submission approved—workflow complete.'
                    : 'Submission returned to user for corrections.'
            );
    }
    
    
}
