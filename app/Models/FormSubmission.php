<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FormSubmission extends Model
{
    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        // stable external reference
        'submission_reference',

        // core data
        'user_id', 'vendor_name', 'schema_group', 'account_group',
        'beneficiary_tin', 'fax', 'bank_name', 'bank_account_no',
        'vendor_email', 'region', 'purchasing_group', 'term_of_payment',
        'contact_title', 'contact_gender', 'contact_first_name',
        'contact_last_name', 'contact_address',
        'contact_telephone_primary', 'contact_telephone_secondary',
        'document_path', 'status',

        // audit / finance metadata
        'audited_by', 'audited_at', 'audit_comment',
        'finance_by', 'finance_at', 'finance_comment',

        // audit approval flags
        'audit_document_ok','audit_vendor_name_ok','audit_schema_group_ok',
        'audit_account_group_ok','audit_beneficiary_tin_ok','audit_fax_ok',
        'audit_bank_name_ok','audit_bank_account_no_ok','audit_vendor_email_ok',
        'audit_region_ok','audit_purchasing_group_ok','audit_term_of_payment_ok',
        'audit_contact_title_ok','audit_contact_gender_ok',
        'audit_contact_first_name_ok','audit_contact_last_name_ok',
        'audit_contact_address_ok','audit_contact_telephone_primary_ok',
        'audit_contact_telephone_secondary_ok',

        // finance approval flags
        'finance_document_ok','finance_vendor_name_ok','finance_schema_group_ok',
        'finance_account_group_ok','finance_beneficiary_tin_ok','finance_fax_ok',
        'finance_bank_name_ok','finance_bank_account_no_ok','finance_vendor_email_ok',
        'finance_region_ok','finance_purchasing_group_ok','finance_term_of_payment_ok',
        'finance_contact_title_ok','finance_contact_gender_ok',
        'finance_contact_first_name_ok','finance_contact_last_name_ok',
        'finance_contact_address_ok','finance_contact_telephone_primary_ok',
        'finance_contact_telephone_secondary_ok',
    ]; 

    /**
     * Type casting for attributes.
     */
    protected $casts = [
        // timestamps
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'audited_at'  => 'datetime',
        'finance_at'  => 'datetime',

        // stable reference
        'submission_reference' => 'string',

        // audit approval flags
        'audit_document_ok'            => 'boolean',
        'audit_vendor_name_ok'         => 'boolean',
        'audit_schema_group_ok'        => 'boolean',
        'audit_account_group_ok'       => 'boolean',
        'audit_beneficiary_tin_ok'     => 'boolean',
        'audit_fax_ok'                 => 'boolean',
        'audit_bank_name_ok'           => 'boolean',
        'audit_bank_account_no_ok'     => 'boolean',
        'audit_vendor_email_ok'        => 'boolean',
        'audit_region_ok'              => 'boolean',
        'audit_purchasing_group_ok'    => 'boolean',
        'audit_term_of_payment_ok'     => 'boolean',
        'audit_contact_title_ok'               => 'boolean',
        'audit_contact_gender_ok'              => 'boolean',
        'audit_contact_first_name_ok'          => 'boolean',
        'audit_contact_last_name_ok'           => 'boolean',
        'audit_contact_address_ok'             => 'boolean',
        'audit_contact_telephone_primary_ok'   => 'boolean',
        'audit_contact_telephone_secondary_ok' => 'boolean',

        // finance approval flags
        'finance_document_ok'            => 'boolean',
        'finance_vendor_name_ok'         => 'boolean',
        'finance_schema_group_ok'        => 'boolean',
        'finance_account_group_ok'       => 'boolean',
        'finance_beneficiary_tin_ok'     => 'boolean',
        'finance_fax_ok'                 => 'boolean',
        'finance_bank_name_ok'           => 'boolean',
        'finance_bank_account_no_ok'     => 'boolean',
        'finance_vendor_email_ok'        => 'boolean',
        'finance_region_ok'              => 'boolean',
        'finance_purchasing_group_ok'    => 'boolean',
        'finance_term_of_payment_ok'     => 'boolean',
        'finance_contact_title_ok'               => 'boolean',
        'finance_contact_gender_ok'              => 'boolean',
        'finance_contact_first_name_ok'          => 'boolean',
        'finance_contact_last_name_ok'           => 'boolean',
        'finance_contact_address_ok'             => 'boolean',
        'finance_contact_telephone_primary_ok'   => 'boolean',
        'finance_contact_telephone_secondary_ok' => 'boolean',
    ];

    /**
     * Boot method to generate a stable UUID reference on first create.
     */
    protected static function booted()
    {
        static::creating(function ($submission) {
            if (! $submission->submission_reference) {
                $submission->submission_reference = (string) Str::uuid();
            }
        });
    }

    /**
     * The form belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who audited the submission.
     */
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    /**
     * The user who handled finance on the submission.
     */
    public function financier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finance_by');
    }
}
