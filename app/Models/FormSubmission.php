<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class FormSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'extra_data',
        'document_path',
        'status',
        'audited_by',
        'audited_at',
        'audit_comment',
        'finance_by',
        'finance_at',
        'finance_comment',

            // Vendor Info
    'vendor_name','schema_group','account_group','beneficiary_tin','fax',
    'bank_name','bank_account_no','vendor_email','region',
    'purchasing_group','term_of_payment',
    // Contact Person
    'contact_title','contact_gender','contact_first_name','contact_last_name',
    'contact_address','contact_telephone_primary','contact_telephone_secondary',
    // ...other existing fields...
    ];

    /**
     * Cast dates to Carbon instances.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'audited_at' => 'datetime',
        'finance_at'  => 'datetime',
    ];

    /**
     * The form belongs to the submitting user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The form was audited by this user.
     */
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    public function financier(): BelongsTo
{
    return $this->belongsTo(User::class, 'finance_by');
}
}
