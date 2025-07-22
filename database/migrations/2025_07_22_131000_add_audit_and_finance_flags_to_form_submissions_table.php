<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            // Audit flags
            $table->boolean('audit_document_ok')->default(true);
            $table->boolean('audit_vendor_name_ok')->default(true);
            $table->boolean('audit_schema_group_ok')->default(true);
            $table->boolean('audit_account_group_ok')->default(true);
            $table->boolean('audit_beneficiary_tin_ok')->default(true);
            $table->boolean('audit_fax_ok')->default(true);
            $table->boolean('audit_bank_name_ok')->default(true);
            $table->boolean('audit_bank_account_no_ok')->default(true);
            $table->boolean('audit_vendor_email_ok')->default(true);
            $table->boolean('audit_region_ok')->default(true);
            $table->boolean('audit_purchasing_group_ok')->default(true);
            $table->boolean('audit_term_of_payment_ok')->default(true);

            $table->boolean('audit_contact_title_ok')->default(true);
            $table->boolean('audit_contact_gender_ok')->default(true);
            $table->boolean('audit_contact_first_name_ok')->default(true);
            $table->boolean('audit_contact_last_name_ok')->default(true);
            $table->boolean('audit_contact_address_ok')->default(true);
            $table->boolean('audit_contact_telephone_primary_ok')->default(true);
            $table->boolean('audit_contact_telephone_secondary_ok')->default(true);

            // Finance flags
            $table->boolean('finance_document_ok')->default(true);
            $table->boolean('finance_vendor_name_ok')->default(true);
            $table->boolean('finance_schema_group_ok')->default(true);
            $table->boolean('finance_account_group_ok')->default(true);
            $table->boolean('finance_beneficiary_tin_ok')->default(true);
            $table->boolean('finance_fax_ok')->default(true);
            $table->boolean('finance_bank_name_ok')->default(true);
            $table->boolean('finance_bank_account_no_ok')->default(true);
            $table->boolean('finance_vendor_email_ok')->default(true);
            $table->boolean('finance_region_ok')->default(true);
            $table->boolean('finance_purchasing_group_ok')->default(true);
            $table->boolean('finance_term_of_payment_ok')->default(true);

            $table->boolean('finance_contact_title_ok')->default(true);
            $table->boolean('finance_contact_gender_ok')->default(true);
            $table->boolean('finance_contact_first_name_ok')->default(true);
            $table->boolean('finance_contact_last_name_ok')->default(true);
            $table->boolean('finance_contact_address_ok')->default(true);
            $table->boolean('finance_contact_telephone_primary_ok')->default(true);
            $table->boolean('finance_contact_telephone_secondary_ok')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            // Drop all of them in reverse
            $cols = [
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
                'finance_region_ok','finance_purchasing_group_ok',
                'finance_term_of_payment_ok','finance_contact_title_ok',
                'finance_contact_gender_ok','finance_contact_first_name_ok',
                'finance_contact_last_name_ok','finance_contact_address_ok',
                'finance_contact_telephone_primary_ok','finance_contact_telephone_secondary_ok',
            ];
            $table->dropColumn($cols);
        });
    }
};
