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
        Schema::table('customers', function (Blueprint $table) {
            // cek dulu mana yang BELUM ada di tabel, baru tambahin
            if (!Schema::hasColumn('customers', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('customers', 'currency')) {
                $table->string('currency', 10)->nullable();
            }
            if (!Schema::hasColumn('customers', 'potential_revenue')) {
                $table->decimal('potential_revenue', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('customers', 'last_followup_date')) {
                $table->date('last_followup_date')->nullable();
            }
            if (!Schema::hasColumn('customers', 'next_followup_date')) {
                $table->date('next_followup_date')->nullable();
            }
            if (!Schema::hasColumn('customers', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'assigned_staff',
                'status',
                'currency',
                'potential_revenue',
                'last_followup_date',
                'next_followup_date',
                'description',
            ]);
        });
    }

};
