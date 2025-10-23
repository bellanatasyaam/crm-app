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
            $table->timestamps();
            
            $table->softDeletes();
            
            $table->foreignId('company_id')
                  ->nullable() 
                  ->constrained('companies')
                  ->onDelete('set null');
            
            $table->string('name');
            $table->string('imo_number')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('port_of_call')->nullable();
            $table->string('flag')->default('Indonesia');
            $table->string('vessel_type');

            $table->decimal('gross_tonnage', 10, 2)->nullable();
            $table->decimal('net_tonnage', 10, 2)->nullable();
            $table->integer('year_built')->nullable();

            $table->enum('status', ['active', 'maintenance', 'retired'])->default('active');
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
