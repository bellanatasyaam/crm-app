<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('customer_type')->nullable();
            $table->date('last_follow_up')->nullable();
            $table->date('next_follow_up')->nullable()->after('last_follow_up');
            $table->text('remark')->nullable()->after('next_follow_up');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['customer_type', 'last_follow_up', 'next_follow_up', 'remark']);
        });
    }
};
