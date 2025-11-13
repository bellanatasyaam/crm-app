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
        Schema::table('vessels', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
        });
    }

};
