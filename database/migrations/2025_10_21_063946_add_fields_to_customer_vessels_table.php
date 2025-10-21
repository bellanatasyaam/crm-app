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
        Schema::table('customer_vessels', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->decimal('potential_revenue', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->date('last_followup_date')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->text('description')->nullable();
            $table->text('remark')->nullable();
        });
    }

    public function down()
    {
        Schema::table('customer_vessels', function (Blueprint $table) {
            $table->dropColumn([
                'status', 
                'potential_revenue', 
                'currency', 
                'last_followup_date', 
                'next_followup_date', 
                'description', 
                'remark'
            ]);
        });
    }
};
