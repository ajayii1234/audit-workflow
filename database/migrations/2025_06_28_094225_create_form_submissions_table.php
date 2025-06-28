<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();

            // Who submitted the form
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Replace these with whatever fields your form needs:
            $table->string('title');
            $table->text('description');
            // e.g. additional data as JSON
            $table->json('extra_data')->nullable();

            // Workflow status
            $table->enum('status', [
                'pending_audit',
                'returned_to_user',
                'pending_finance',
                'approved'
            ])->default('pending_audit');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
