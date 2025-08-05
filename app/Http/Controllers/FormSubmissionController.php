<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormSubmissionController extends Controller
{
    /**
     * Show the user form.
     */
    public function create()
    {
        return view('user.form');
    }


    public function index()
    {
        $subs = FormSubmission::where('user_id', Auth::id())
                 ->orderBy('created_at','desc')
                 ->get();

        return view('user.submissions.index', [
          'submissions' => $subs,
        ]);
    }


    public function show(FormSubmission $submission)
{
    // Authorize: ensure the submission belongs to this user
    abort_if($submission->user_id !== Auth::id(), 403);

    return view('user.submissions.show', [
      'submission' => $submission,
    ]);
}


    /**
     * Store a new submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            
        
            'document'    => ['nullable','file','max:5120'], // max 5MB

                    // Vendor Info
        'vendor_name'              => ['nullable','string','max:255'],
        'schema_group'             => ['nullable','in:EMANL Import,EMANL Local'],
        'account_group'            => ['nullable','in:Affiliate,Third Party Local,Third Party Import'],
        'beneficiary_tin'          => ['nullable','string','max:100'],
        'fax'                      => ['nullable','string','max:100'],
        'bank_name'                => ['nullable','string','max:255'],
        'bank_account_no'          => ['nullable','string','max:100'],
        'vendor_email'             => ['nullable','email','max:255'],
        'region'                   => ['nullable','string','max:255'],
        'purchasing_group'         => ['nullable','in:Stock Material,Non-Stock Material,Services'],
        'term_of_payment'          => ['nullable','in:Cash,Within 7 Days Due net,Within 14 Days Due net,Within 15 Days Due net,Within 21 Days Due net,Within 30 Days Due net,Within 35 Days Due net,Within 60 Days Due net,Within 90 Days Due net,Within 120 Days Due net'],
        // Contact Person
        'contact_title'            => ['nullable','in:Mr,Mrs,Miss'],
        'contact_gender'           => ['nullable','in:Male,Female'],
        'contact_first_name'       => ['nullable','string','max:255'],
        'contact_last_name'        => ['nullable','string','max:255'],
        'contact_address'          => ['nullable','string'],
        'contact_telephone_primary'=> ['nullable','string','max:50'],
        'contact_telephone_secondary'=> ['nullable','string','max:50'],
        ]);
    
        // Handle file upload
        if ($file = $request->file('document')) {
            $data['document_path'] = $file->store('documents', 'public');
        }
    
        $submission = $request->user()
                              ->submissions()
                              ->create($data);
    
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
        // Only the owner can update and only if it was returned for correction
        abort_if(
            $submission->user_id !== auth()->id() ||
            $submission->status !== 'returned_to_user',
            403
        );

        // Validate text fields + optional upload
        $data = $request->validate([
            'document'    => ['nullable', 'file', 'max:5120'], // max 5MB

            // Vendor Info
            'vendor_name'      => ['nullable','string','max:255'],
            'schema_group'     => ['nullable','in:EMANL Import,EMANL Local'],
            'account_group'    => ['nullable','in:Affiliate,Third Party Local,Third Party Import'],
            'beneficiary_tin'  => ['nullable','string','max:100'],
            'fax'              => ['nullable','string','max:100'],
            'bank_name'        => ['nullable','string','max:255'],
            'bank_account_no'  => ['nullable','string','max:100'],
            'vendor_email'     => ['nullable','email','max:255'],
            'region'           => ['nullable','string','max:255'],
            'purchasing_group' => ['nullable','in:Stock Material,Non-Stock Material,Services'],
            'term_of_payment'  => [
                'nullable',
                'in:Cash,Within 7 Days Due net,Within 14 Days Due net,Within 15 Days Due net,'
                .'Within 21 Days Due net,Within 30 Days Due net,Within 35 Days Due net,'
                .'Within 60 Days Due net,Within 90 Days Due net,Within 120 Days Due net'
            ],

            // Contact Person
            'contact_title'               => ['nullable','in:Mr,Mrs,Miss'],
            'contact_gender'              => ['nullable','in:Male,Female'],
            'contact_first_name'          => ['nullable','string','max:255'],
            'contact_last_name'           => ['nullable','string','max:255'],
            'contact_address'             => ['nullable','string'],
            'contact_telephone_primary'   => ['nullable','string','max:50'],
            'contact_telephone_secondary' => ['nullable','string','max:50'],
        ]);

        // Handle new document upload (if provided)
        if ($file = $request->file('document')) {
            // Store on the public disk so asset('storage/...') can serve it
            $data['document_path'] = $file->store('documents', 'public');
        } elseif ($submission->document_path) {
            // Carry over existing path if no new upload
            $data['document_path'] = $submission->document_path;
        }

        // Clone the old “returned” submission so its record remains in the DB
        $new = $submission->replicate([
            // we only replicate the raw form fields, not statuses or audit/finance flags
            'vendor_name','schema_group','account_group',
            'beneficiary_tin','fax','bank_name','bank_account_no',
            'vendor_email','region','purchasing_group','term_of_payment',
            'contact_title','contact_gender','contact_first_name',
            'contact_last_name','contact_address',
            'contact_telephone_primary','contact_telephone_secondary',
            'document_path',
        ]);

        // Overwrite with the validated (and possibly new) data
        $new->fill(array_merge($data, [
            'user_id' => auth()->id(),
            'status'  => 'pending_audit',
            // reset any audit/finance columns if you have them:
            'audited_by'    => null,
            'audited_at'    => null,
            'audit_comment' => null,
            'finance_by'      => null,
            'finance_at'      => null,
            'finance_comment' => null,
            // and any *_ok flags:
            // e.g. 'audit_document_ok' => null, etc...
        ]));

        $new->save();

        return redirect()
            ->route('user.submissions.returned')
            ->with('success', 'Submission updated and resubmitted for audit.');
    }

    public function search(Request $request)
    {
        $userId = $request->user()->id;
        $term   = trim($request->query('term', ''));
    
        $query = FormSubmission::where('user_id', $userId);
    
        if ($term !== '') {
            $query->where(function($q) use ($term) {
                $columns = [
                    'id','vendor_name','schema_group','account_group',
                    'beneficiary_tin','fax','bank_name','bank_account_no',
                    'vendor_email','region','purchasing_group','term_of_payment',
                    'contact_title','contact_gender','contact_first_name',
                    'contact_last_name','contact_address',
                    'contact_telephone_primary','contact_telephone_secondary'
                ];
    
                // Search data‐columns
                foreach ($columns as $col) {
                    if ($col === 'id' && is_numeric($term)) {
                        $q->orWhere($col, $term);
                    } else {
                        $q->orWhere($col, 'like', "%{$term}%");
                    }
                }
    
                // Also search by status label
                $statusMap = [
                    'pending audit'   => 'pending_audit',
                    'pending finance' => 'pending_finance',
                    'returned'        => 'returned_to_user',
                    'approved'        => 'approved',
                ];
                foreach ($statusMap as $label => $code) {
                    if (stripos($label, $term) !== false || stripos($term, $label) !== false) {
                        $q->orWhere('status', $code);
                    }
                }
            });
        }
    
        $submissions = $query
            ->orderBy('created_at','desc')
            ->get();
    
        return view('user.submissions.search', [
            'submissions' => $submissions,
            'term'        => $term,
        ]);
    }
    
}
