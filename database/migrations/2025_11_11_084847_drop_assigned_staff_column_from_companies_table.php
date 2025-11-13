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
            if (Schema::hasColumn('companies', 'assigned_staff')) {
                $table->dropColumn('assigned_staff');
            }
            if (Schema::hasColumn('companies', 'assigned_staff_email')) {
                $table->dropColumn('assigned_staff_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('assigned_staff')->nullable();
            $table->string('assigned_staff_email')->nullable();
        });
    }

};
