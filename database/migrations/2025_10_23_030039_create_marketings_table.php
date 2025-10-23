<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketings', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('project_name')->nullable();
            $table->string('staff')->nullable();
            $table->string('status')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->date('last_contact')->nullable();
            $table->date('next_follow_up')->nullable();
            $table->text('remark')->nullable();

            // kolom tambahan
            $table->string('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('vessel_name')->nullable();
            $table->decimal('revenue', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketings');
    }
};

