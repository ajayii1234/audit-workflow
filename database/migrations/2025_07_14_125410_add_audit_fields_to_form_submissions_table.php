<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_audit_fields_to_form_submissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->foreignId('audited_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('status');
            $table->timestamp('audited_at')
                  ->nullable()
                  ->after('audited_by');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('audited_by');
            $table->dropColumn('audited_at');
        });
    }
};

