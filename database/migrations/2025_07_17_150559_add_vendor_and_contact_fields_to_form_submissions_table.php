<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            // Vendor Information
            $table->string('vendor_name')->nullable()->after('description');
            $table->string('schema_group')->nullable()->after('vendor_name');
            $table->string('account_group')->nullable()->after('schema_group');
            $table->string('beneficiary_tin')->nullable()->after('account_group');
            $table->string('fax')->nullable()->after('beneficiary_tin');
            $table->string('bank_name')->nullable()->after('fax');
            $table->string('bank_account_no')->nullable()->after('bank_name');
            $table->string('vendor_email')->nullable()->after('bank_account_no');
            $table->string('region')->nullable()->after('vendor_email');
            $table->string('purchasing_group')->nullable()->after('region');
            $table->string('term_of_payment')->nullable()->after('purchasing_group');

            // Contact Person
            $table->string('contact_title')->nullable()->after('term_of_payment');
            $table->string('contact_gender')->nullable()->after('contact_title');
            $table->string('contact_first_name')->nullable()->after('contact_gender');
            $table->string('contact_last_name')->nullable()->after('contact_first_name');
            $table->text('contact_address')->nullable()->after('contact_last_name');
            $table->string('contact_telephone_primary')->nullable()->after('contact_address');
            $table->string('contact_telephone_secondary')->nullable()->after('contact_telephone_primary');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_name','schema_group','account_group','beneficiary_tin','fax',
                'bank_name','bank_account_no','vendor_email','region','purchasing_group',
                'term_of_payment','contact_title','contact_gender','contact_first_name',
                'contact_last_name','contact_address','contact_telephone_primary',
                'contact_telephone_secondary'
            ]);
        });
    }
};
