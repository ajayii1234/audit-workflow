<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_add_submission_reference_to_form_submissions.php

public function up()
{
    Schema::table('form_submissions', function (Blueprint $table) {
        $table->string('submission_reference', 36)
              ->after('id')
              ->unique()
              ->nullable(false);
    });
}

public function down()
{
    Schema::table('form_submissions', function (Blueprint $table) {
        $table->dropColumn('submission_reference');
    });
}

};
