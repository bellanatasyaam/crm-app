<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Customer Type (Prospect, Existing, dll)
            $table->string('customer_type')->nullable()->after('tax_id');

            // Follow-up fields
            $table->date('last_follow_up')->nullable()->after('status');
            $table->date('next_follow_up')->nullable()->after('last_follow_up');

            // Remark / Notes
            $table->text('remark')->nullable()->after('next_follow_up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['customer_type', 'last_follow_up', 'next_follow_up', 'remark']);
        });
    }
};
