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
            $table->date('next_followup_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropColumn('next_followup_date');
        });
    }
};
