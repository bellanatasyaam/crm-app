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
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('vessel_name');
            $table->string('port_of_call')->nullable();
            $table->decimal('estimate_revenue', 15, 2)->nullable();
            $table->string('currency', 10)->default('USD');

            $table->text('description')->nullable();
            $table->string('remark')->nullable();

            $table->string('status')->default('Follow Up'); 
            // Follow up, On progress, Request, Waiting approval, Approve, On going, Quotation sent, Done/Closing

            $table->date('last_contact')->nullable();
            $table->date('next_follow_up')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
