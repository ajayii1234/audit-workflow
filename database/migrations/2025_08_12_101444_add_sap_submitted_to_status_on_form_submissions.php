<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'sap_submitted' to the allowed enum values
        DB::statement("
            ALTER TABLE `form_submissions`
            MODIFY `status` ENUM(
                'pending_audit',
                'returned_to_user',
                'pending_finance',
                'approved',
                'sap_submitted'
            ) NOT NULL DEFAULT 'pending_audit'
        ");
    }

    public function down(): void
    {
        // Revert (be careful if rows already have sap_submitted)
        DB::statement("
            ALTER TABLE `form_submissions`
            MODIFY `status` ENUM(
                'pending_audit',
                'returned_to_user',
                'pending_finance',
                'approved'
            ) NOT NULL DEFAULT 'pending_audit'
        ");
    }
};
