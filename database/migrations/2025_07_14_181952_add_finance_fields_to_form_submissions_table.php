<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->foreignId('finance_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('audit_comment');
            $table->timestamp('finance_at')
                  ->nullable()
                  ->after('finance_by');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('finance_by');
            $table->dropColumn('finance_at');
        });
    }
};

