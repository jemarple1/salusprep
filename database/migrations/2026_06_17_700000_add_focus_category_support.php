<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->string('focus_category', 100)->nullable()->after('certification_level');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->string('pinned_focus_category', 100)->nullable()->after('unlocked_at');
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn('focus_category');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->dropColumn('pinned_focus_category');
        });
    }
};
