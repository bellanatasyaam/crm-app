<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'assigned_staff_id')) {
                $table->unsignedBigInteger('assigned_staff_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('customers', 'assigned_staff')) {
                $table->string('assigned_staff')->nullable()->after('assigned_staff_id');
            }

            if (!Schema::hasColumn('customers', 'assigned_staff_email')) {
                $table->string('assigned_staff_email')->nullable()->after('assigned_staff');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'assigned_staff_id')) {
                $table->dropColumn('assigned_staff_id');
            }

            if (Schema::hasColumn('customers', 'assigned_staff')) {
                $table->dropColumn('assigned_staff');
            }

            if (Schema::hasColumn('customers', 'assigned_staff_email')) {
                $table->dropColumn('assigned_staff_email');
            }
        });
    }
};
