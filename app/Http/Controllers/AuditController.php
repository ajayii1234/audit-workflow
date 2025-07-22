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
            'document_ok'            => 'required|boolean',
            'vendor_name_ok'         => 'required|boolean',
            'schema_group_ok'        => 'required|boolean',
            'account_group_ok'       => 'required|boolean',
            'beneficiary_tin_ok'     => 'required|boolean',
            'fax_ok'                 => 'required|boolean',
            'bank_name_ok'           => 'required|boolean',
            'bank_account_no_ok'     => 'required|boolean',
            'vendor_email_ok'        => 'required|boolean',
            'region_ok'              => 'required|boolean',
            'purchasing_group_ok'    => 'required|boolean',
            'term_of_payment_ok'     => 'required|boolean',
            'contact_title_ok'               => 'required|boolean',
            'contact_gender_ok'              => 'required|boolean',
            'contact_first_name_ok'          => 'required|boolean',
            'contact_last_name_ok'           => 'required|boolean',
            'contact_address_ok'             => 'required|boolean',
            'contact_telephone_primary_ok'   => 'required|boolean',
            'contact_telephone_secondary_ok' => 'required|boolean',
            'audit_comment'                   => 'nullable|string|max:1000',
        ]);
    
        // Collect all the flags
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
    
        // Now use loose truthiness instead of strict === true
        $allOk = collect($allFlags)->every(fn($v) => (bool) $v);
    
        $submission->update([
            'audit_document_ok'            => $data['document_ok'],
            'audit_vendor_name_ok'         => $data['vendor_name_ok'],
            'audit_schema_group_ok'        => $data['schema_group_ok'],
            'audit_account_group_ok'       => $data['account_group_ok'],
            'audit_beneficiary_tin_ok'     => $data['beneficiary_tin_ok'],
            'audit_fax_ok'                 => $data['fax_ok'],
            'audit_bank_name_ok'           => $data['bank_name_ok'],
            'audit_bank_account_no_ok'     => $data['bank_account_no_ok'],
            'audit_vendor_email_ok'        => $data['vendor_email_ok'],
            'audit_region_ok'              => $data['region_ok'],
            'audit_purchasing_group_ok'    => $data['purchasing_group_ok'],
            'audit_term_of_payment_ok'     => $data['term_of_payment_ok'],
            'audit_contact_title_ok'               => $data['contact_title_ok'],
            'audit_contact_gender_ok'              => $data['contact_gender_ok'],
            'audit_contact_first_name_ok'          => $data['contact_first_name_ok'],
            'audit_contact_last_name_ok'           => $data['contact_last_name_ok'],
            'audit_contact_address_ok'             => $data['contact_address_ok'],
            'audit_contact_telephone_primary_ok'   => $data['contact_telephone_primary_ok'],
            'audit_contact_telephone_secondary_ok' => $data['contact_telephone_secondary_ok'],
            'audit_comment'                  => $data['audit_comment'] ?? null,
            'status'                         => $allOk ? 'pending_finance' : 'returned_to_user',
            'audited_by'                     => $request->user()->id,
            'audited_at'                     => now(),
        ]);
    
        return redirect()
            ->route('audit.submissions.index')
            ->with('success',
                $allOk
                    ? 'Submission approved and sent to Finance.'
                    : 'Submission returned to user for corrections.'
            );
    }
    
    


}
