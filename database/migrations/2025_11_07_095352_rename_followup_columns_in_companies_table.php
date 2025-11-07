<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // rename kalau kolom lama masih ada dan kolom baru belum ada
        if (Schema::hasColumn('companies', 'last_follow_up') && !Schema::hasColumn('companies', 'last_followup_date')) {
            DB::statement('ALTER TABLE companies CHANGE last_follow_up last_followup_date DATE NULL;');
        }

        if (Schema::hasColumn('companies', 'next_follow_up') && !Schema::hasColumn('companies', 'next_followup_date')) {
            DB::statement('ALTER TABLE companies CHANGE next_follow_up next_followup_date DATE NULL;');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('companies', 'last_followup_date') && !Schema::hasColumn('companies', 'last_follow_up')) {
            DB::statement('ALTER TABLE companies CHANGE last_followup_date last_follow_up DATE NULL;');
        }

        if (Schema::hasColumn('companies', 'next_followup_date') && !Schema::hasColumn('companies', 'next_follow_up')) {
            DB::statement('ALTER TABLE companies CHANGE next_followup_date next_follow_up DATE NULL;');
        }
    }
};
