<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    protected $fillable = [
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
        // NOTE: you don’t need to add the *_ok flags here if you’re using guarded = []
        // but you can include them if you prefer explicit fillable
        'audit_document_ok','audit_vendor_name_ok','audit_schema_group_ok',
        'audit_account_group_ok','audit_beneficiary_tin_ok','audit_fax_ok',
        'audit_bank_name_ok','audit_bank_account_no_ok','audit_vendor_email_ok',
        'audit_region_ok','audit_purchasing_group_ok','audit_term_of_payment_ok',
        'audit_contact_title_ok','audit_contact_gender_ok',
        'audit_contact_first_name_ok','audit_contact_last_name_ok',
        'audit_contact_address_ok','audit_contact_telephone_primary_ok',
        'audit_contact_telephone_secondary_ok',
        'finance_document_ok','finance_vendor_name_ok','finance_schema_group_ok',
        'finance_account_group_ok','finance_beneficiary_tin_ok','finance_fax_ok',
        'finance_bank_name_ok','finance_bank_account_no_ok','finance_vendor_email_ok',
        'finance_region_ok','finance_purchasing_group_ok','finance_term_of_payment_ok',
        'finance_contact_title_ok','finance_contact_gender_ok',
        'finance_contact_first_name_ok','finance_contact_last_name_ok',
        'finance_contact_address_ok','finance_contact_telephone_primary_ok',
        'finance_contact_telephone_secondary_ok',
    ];

    protected $casts = [
        // timestamps
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'audited_at'  => 'datetime',
        'finance_at'  => 'datetime',

        // audit flags
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

        // finance flags
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
     * The form belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who audited.
     */
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    /**
     * The user who handled finance.
     */
    public function financier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finance_by');
    }
}
