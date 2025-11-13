<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('companies', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('companies', 'website')) {
                $table->string('website')->nullable();
            }
            if (!Schema::hasColumn('companies', 'tax_id')) {
                $table->string('tax_id')->nullable();
            }
            if (!Schema::hasColumn('companies', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('companies', 'industry')) {
                $table->string('industry')->nullable();
            }
            if (!Schema::hasColumn('companies', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('companies', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('companies', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('companies', 'customer_tier')) {
                $table->string('customer_tier')->nullable();
            }
            if (!Schema::hasColumn('companies', 'last_followup_date')) {
                $table->date('last_followup_date')->nullable();
            }
            if (!Schema::hasColumn('companies', 'next_followup_date')) {
                $table->date('next_followup_date')->nullable();
            }
            if (!Schema::hasColumn('companies', 'remark')) {
                $table->text('remark')->nullable();
            }
            if (!Schema::hasColumn('companies', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            if (!Schema::hasColumn('companies', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'phone',
                'website',
                'tax_id',
                'type',
                'industry',
                'address',
                'city',
                'country',
                'customer_tier',
                'last_followup_date',
                'next_followup_date',
                'remark',
                'created_by',
                'updated_by',
            ]);
        });
    }
};

