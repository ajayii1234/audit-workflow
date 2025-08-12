<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('form_submissions', 'it_submitted')) {
                $table->boolean('it_submitted')->default(false)->after('finance_comment');
            }
            if (!Schema::hasColumn('form_submissions', 'it_by')) {
                $table->unsignedBigInteger('it_by')->nullable()->after('it_submitted');
            }
            if (!Schema::hasColumn('form_submissions', 'it_at')) {
                $table->timestamp('it_at')->nullable()->after('it_by');
            }
            if (!Schema::hasColumn('form_submissions', 'it_comment')) {
                $table->text('it_comment')->nullable()->after('it_at');
            }
        });
    }
    
    public function down()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn(['it_comment', 'it_at', 'it_by', 'it_submitted']);
        });
    }
    
    
};
